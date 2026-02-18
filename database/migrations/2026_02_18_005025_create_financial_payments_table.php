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
        Schema::create('financial_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            $table->foreignId('financial_transaction_id')
                ->constrained('financial_transactions')
                ->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->boolean('is_manual')->default(true)->comment('Se não veio de extrato');
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->index(['financial_transaction_id', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_payments');
    }
};
