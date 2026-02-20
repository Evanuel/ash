<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialPayment;
use App\Models\Person;
use App\Models\PaymentMethod;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinancialTransactionImportController extends Controller
{
    private $paymentMethodsMap = [];

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        // Read file contents, handling UTF-8 correctly
        $content = file_get_contents($path);
        $rows = explode("\n", $content);

        // Remove empty rows
        $rows = array_filter($rows, fn($row) => !empty(trim($row)));

        // Skip header
        $header = array_shift($rows);
        $delimiter = strpos($header, ';') !== false ? ';' : ',';

        $importedCount = 0;
        $errors = [];

        $this->loadPaymentMethods();

        foreach ($rows as $index => $row) {
            $data = str_getcsv($row, $delimiter);

            try {
                DB::transaction(function () use ($data, &$importedCount) {
                    $this->processRow($data);
                    $importedCount++;
                });
            } catch (\Exception $e) {
                Log::error("Error importing row " . ($index + 2) . ": " . $e->getMessage());
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        return response()->json([
            'message' => 'Import completed',
            'imported' => $importedCount,
            'errors' => $errors,
        ]);
    }

    private function processRow(array $data)
    {
        $clientId = auth()->user()->client_id;
        $userId = auth()->id();

        // 1. Beneficiary (Person or Company)
        $beneficiaryName = $data[6] ?? null;
        $personId = null;
        $companyId = null;
        $beneficiaryColumn = null;
        $personType = FinancialTransaction::PERSON_UNKNOWN;

        if ($beneficiaryName) {
            // First check for Company
            $company = \App\Models\Company::where('client_id', $clientId)
                ->where(function ($q) use ($beneficiaryName) {
                    $q->where('trade_name', 'LIKE', $beneficiaryName)
                        ->orWhere('company_name', 'LIKE', $beneficiaryName);
                })
                ->first();

            if ($company) {
                $companyId = $company->id;
                $personType = FinancialTransaction::PERSON_COMPANY;
            } else {
                // Then check for Person
                $person = Person::where('client_id', $clientId)
                    ->where(DB::raw("CONCAT(first_name, ' ', COALESCE(last_name, ''))"), 'LIKE', $beneficiaryName)
                    ->first();

                if ($person) {
                    $personId = $person->id;
                    $personType = FinancialTransaction::PERSON_INDIVIDUAL;
                } else {
                    // Fallback to beneficiary field
                    $beneficiaryColumn = $beneficiaryName;
                    $personType = FinancialTransaction::PERSON_UNKNOWN;
                }
            }
        }

        // 2. Dates
        $competencyDate = $this->parseCsvDate($data[1] ?? null);
        $dueDate = $this->parseCsvDate($data[8] ?? null) ?? now();
        $paymentDate = $this->parseCsvDate($data[12] ?? null);

        // 3. Amount and Paid Amount
        $amountValue = $this->parseCsvDecimal($data[7] ?? 0);
        $paidTotal = $this->parseCsvDecimal($data[11] ?? 0);

        // 4. Payment Method
        $paymentMethodName = $data[16] ?? null;
        $paymentMethodId = $this->mapPaymentMethod($paymentMethodName);

        // 5. Create Transaction
        $transaction = FinancialTransaction::create([
            'client_id' => $clientId,
            'type_id' => FinancialTransaction::TYPE_PAYABLE,
            'description' => $data[4] ?? 'Imported Transaction',
            'fiscal_document' => $data[2] ?? null,
            'cost_center' => $data[3] ?? null,
            'individual_id' => $personId,
            'company_id' => $companyId,
            'beneficiary' => $beneficiaryColumn,
            'person_type' => $personType,
            'amount' => $amountValue <= 0 ? 0.01 : $amountValue, // Ensure min 0.01 if numeric
            'due_date' => $dueDate,
            'competency_date' => $competencyDate,
            'status_id' => 1,
            'installment' => (int) ($data[13] ?? 1),
            'total_installments' => (int) ($data[14] ?? 1),
            'transaction_key' => $data[15] ?? '0',
            'notes' => $data[17] ?? null,
            'approval_status' => 'pending_review',
            'origin' => 'imported',
            'created_by' => $userId,
        ]);

        // 6. Record Payment if exists
        if ($paidTotal > 0 && $paymentDate) {
            FinancialPayment::create([
                'client_id' => $clientId,
                'financial_transaction_id' => $transaction->id,
                'payment_date' => $paymentDate,
                'amount' => $paidTotal,
                'payment_method_id' => $paymentMethodId ?? 1,
                'is_manual' => false,
                'created_by' => $userId,
            ]);

            // Update transaction payment totals (FinancialTransaction::registerPayment logic)
            $transaction->paid_total = $paidTotal;
            if ($transaction->paid_total >= ($transaction->amount + $transaction->interest_amount + $transaction->fine_amount - $transaction->discount_amount)) {
                $transaction->is_fully_paid = true;
            }
            $transaction->save();
        }
    }

    private function parseCsvDate($value)
    {
        if (empty(trim($value)))
            return null;

        // Handle Excel serial date
        if (is_numeric($value) && (int) $value > 40000) {
            // Excel dates start from 1900-01-01 (serial 1)
            // PHP/UNIX starts from 1970-01-01
            // The difference is 25569 days
            return Carbon::createFromTimestamp((($value - 25569) * 86400));
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseCsvDecimal($value)
    {
        if (empty(trim($value)))
            return 0;
        // Handle cases like 782,73 or 1.000,00
        $clean = str_replace('.', '', $value);
        $clean = str_replace(',', '.', $clean);
        return (float) $clean;
    }

    private function loadPaymentMethods()
    {
        $this->paymentMethodsMap = PaymentMethod::all()->pluck('id', 'name')->toArray();
    }

    private function mapPaymentMethod($name)
    {
        if (!$name)
            return null;
        $name = strtoupper($name);

        if (isset($this->paymentMethodsMap[$name])) {
            return $this->paymentMethodsMap[$name];
        }

        // Partial matching
        foreach ($this->paymentMethodsMap as $methodName => $id) {
            if (str_contains(strtoupper($methodName), $name) || str_contains($name, strtoupper($methodName))) {
                return $id;
            }
        }

        return null;
    }
}
