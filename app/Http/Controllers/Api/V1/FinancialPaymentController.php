<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\FinancialPayment\StoreFinancialPaymentRequest;
use App\Http\Requests\Api\V1\FinancialPayment\UpdateFinancialPaymentRequest;
use App\Http\Resources\Api\V1\FinancialPaymentResource;
use App\Http\Resources\Api\V1\FinancialPaymentCollection;
use App\Models\FinancialPayment;
use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FinancialPayment::query()
            ->where('client_id', auth()->user()->client_id);

        if ($request->has('financial_transaction_id')) {
            $query->where('financial_transaction_id', $request->financial_transaction_id);
        }

        $items = $query->with(['paymentMethod', 'bank'])->paginate();

        return new FinancialPaymentCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFinancialPaymentRequest $request)
    {
        $data = $request->validated();
        $data['client_id'] = auth()->user()->client_id;
        $data['created_by'] = auth()->id();

        return DB::transaction(function () use ($data) {
            $payment = FinancialPayment::create($data);

            // Update associated transaction
            $transaction = $payment->financialTransaction;
            $transaction->registerPayment((float) $payment->amount);

            return new FinancialPaymentResource($payment->load(['paymentMethod', 'bank']));
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payment = FinancialPayment::where('client_id', auth()->user()->client_id)
            ->with(['paymentMethod', 'bank'])
            ->findOrFail($id);

        return new FinancialPaymentResource($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFinancialPaymentRequest $request, $id)
    {
        $payment = FinancialPayment::where('client_id', auth()->user()->client_id)
            ->findOrFail($id);

        $data = $request->validated();

        return DB::transaction(function () use ($payment, $data) {
            $oldAmount = (float) $payment->amount;
            $payment->update($data);
            $newAmount = (float) $payment->amount;

            if ($oldAmount !== $newAmount) {
                // Adjust the transaction total
                $transaction = $payment->financialTransaction;
                $diff = $newAmount - $oldAmount;
                $transaction->registerPayment($diff);
            }

            return new FinancialPaymentResource($payment->load(['paymentMethod', 'bank']));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = FinancialPayment::where('client_id', auth()->user()->client_id)
            ->findOrFail($id);

        return DB::transaction(function () use ($payment) {
            $transaction = $payment->financialTransaction;

            // Subtract amount from transaction
            $transaction->paid_total -= (float) $payment->amount;

            // Re-calculate is_fully_paid
            $totalDue = ($transaction->amount + $transaction->interest_amount + $transaction->fine_amount - $transaction->discount_amount);
            $transaction->is_fully_paid = $transaction->paid_total >= $totalDue;

            $transaction->save();

            $payment->delete();

            return response()->noContent();
        });
    }
}
