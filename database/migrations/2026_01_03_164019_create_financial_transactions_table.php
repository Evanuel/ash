<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            // Account type: 1 = receivable, 2 = payable
            $table->unsignedBigInteger('type_id')->unsigned()->default(2)->comment('1 = receivable, 2 = payable');

            // Financial document
            $table->string('fiscal_document')->nullable()->comment('NF-e, NFS-e, NFC-e, receipt, etc');
            $table->unsignedBigInteger('fiscal_document_id')->nullable()->comment('Vínculo com o documento fiscal');
            $table->string('cost_center')->nullable()->comment('Centro de custo');
            $table->unsignedBigInteger('cost_center_id')->nullable()->comment('Vínculo com o centro de custo');
            $table->string('description')->nullable()->comment('Descrição');
            $table->date('competency_date')->nullable()->comment('Data de competência (regime de competência)');

            // Categories
            $table->foreignId('category_id')->nullable()->constrained('categories'); //->comment('Categoria')
            $table->foreignId('subcategory_id')->nullable()->constrained('categories'); //->comment('Subcategoria')

            // Person type: 1 = individual, 2 = company
            $table->tinyInteger('person_type')->default(1)->comment('1 = individual, 2 = company');

            // References
            $table->foreignId('individual_id')->nullable()->constrained('people')->comment('Pessoa individual');
            $table->foreignId('company_id')->nullable()->constrained('companies')->comment('Empresa');

            // Dates and amounts
            $table->date('due_date')->nullable(false)->comment('Data de vencimento');
            $table->decimal('amount', 15, 2)->nullable(false)->comment('Valor');

            // Status
            $table->foreignId('status_id')->nullable()->constrained('statuses')->default(1)->comment('Status');

            // Payment information
            $table->string('boleto_url')->nullable()->comment('URL do boleto');
            //$table->date('paid_at')->nullable()->comment('Data de pagamento');
            //$table->decimal('paid_amount', 15, 2)->nullable()->comment('Valor pago');
            //$table->foreignId('bank_id')->nullable()->constrained('banks')->comment('Banco');
            //$table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->comment('Método de pagamento');

            // Installments
            $table->tinyInteger('installment')->default(1)->comment('Parcela');
            $table->tinyInteger('total_installments')->default(1)->comment('Total de parcelas');
            $table->string('transaction_key')->default('0')->comment('Chave de transação, código de barras, chave pix, conta bancária  ');

            // Attachments
            $table->string('receipt_url')->nullable()->comment('URL do recibo');

            // Custom fields
            $table->string('custom_field1')->nullable()->comment('Campo personalizado 1');
            $table->string('custom_field2')->nullable()->comment('Campo personalizado 2');
            $table->string('custom_field3')->nullable()->comment('Campo personalizado 3');
            $table->text('notes')->nullable()->comment('Observações');
            $table->string('css_class')->default('text-warning')->comment('Classe CSS');

            // Approval
            $table->enum('approval_status', [
                'draft',
                'pending_review',
                'approved',
                'rejected',
                'cancelled',
                'in_review',
                'corrected',
            ])->default('draft');


            // Review
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Interest, fine and discount
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('fine_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);

            // Reconciliation
            $table->boolean('is_fully_reconciled')->default(false);
            $table->decimal('reconciled_total', 15, 2)->default(0);

            // Paid
            $table->decimal('paid_total', 15, 2)->default(0);
            $table->boolean('is_fully_paid')->default(false);


            // Origin
            $table->enum('origin', [
                'manual',
                'imported',
                'api',
                'recurrence',
                'reconciliation',
                'other'
            ])->default('manual');

            // Audit
            $table->unsignedBigInteger('created_by')->default(0)->comment('Usuário que criou');
            $table->unsignedBigInteger('updated_by')->default(0)->comment('Usuário que atualizou');
            $table->boolean('archived')->default(false)->comment('Arquivado');
            $table->unsignedBigInteger('archived_by')->nullable()->comment('Usuário que arquivou');
            $table->timestamp('archived_at')->nullable()->comment('Data de arquivamento');
            $table->json('metadata')->nullable()->comment('Metadados');
            $table->json('history')->nullable()->comment('Histórico');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('client_id');
            $table->index('type_id');
            $table->index('due_date');
            $table->index('status_id');
            // $table->index('paid_at');
            $table->index('transaction_key');
            $table->index('archived');
            $table->index(['client_id', 'due_date', 'status_id']);
            $table->index(['client_id', 'type_id', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
