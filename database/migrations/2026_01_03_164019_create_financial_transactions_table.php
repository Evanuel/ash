<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable(false);

            // Account type: 1 = receivable, 2 = payable
            $table->unsignedBigInteger('type_id')->unsigned()->default(2);

            // Financial document
            $table->string('fiscal_document')->nullable()->comment('NF-e, NFS-e, NFC-e, receipt, etc');
            $table->string('cost_center')->nullable();
            $table->string('description')->nullable();

            // Categories
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('subcategory_id')->nullable()->constrained('categories');

            // Person type: 1 = individual, 2 = company
            $table->tinyInteger('person_type')->default(1);

            // References
            $table->foreignId('individual_id')->nullable()->constrained('people');
            $table->foreignId('company_id')->nullable()->constrained('companies');

            // Dates and amounts
            $table->date('due_date')->nullable(false);
            $table->decimal('amount', 15, 2)->nullable(false);

            // Status
            $table->foreignId('status_id')->nullable()->constrained('statuses');

            // Payment information
            $table->string('boleto_url')->nullable();
            $table->date('paid_at')->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods');

            // Installments
            $table->tinyInteger('installment')->default(1);
            $table->tinyInteger('total_installments')->default(1);
            $table->string('transaction_key')->default('0');

            // Attachments
            $table->string('receipt_url')->nullable();

            // Custom fields
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->text('notes')->nullable();
            $table->string('css_class')->default('text-warning');

            // Audit
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('client_id');
            $table->index('type_id');
            $table->index('due_date');
            $table->index('status_id');
            $table->index('paid_at');
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
