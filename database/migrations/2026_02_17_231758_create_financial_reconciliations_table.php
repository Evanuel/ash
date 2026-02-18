<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            $table->foreignId('financial_transaction_id')
                ->constrained('financial_transactions')
                ->cascadeOnDelete();

            $table->foreignId('bank_statement_id')
                ->constrained('bank_statements')
                ->cascadeOnDelete();

            $table->decimal('reconciled_amount', 15, 2);

            $table->unsignedBigInteger('reconciled_by');
            $table->timestamp('reconciled_at');

            $table->boolean('divergent')->default(false);
            $table->text('divergence_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unique([
                'financial_transaction_id',
                'bank_statement_id'
            ], 'unique_reconciliation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reconciliations');
    }
};
