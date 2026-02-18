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
        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            $table->foreignId('bank_id')->constrained('banks');
            $table->date('transaction_date');
            $table->string('description');
            $table->string('document_number')->nullable();

            $table->decimal('amount', 15, 2); // positivo ou negativo
            $table->decimal('balance_after', 15, 2)->nullable();

            $table->string('external_id')->nullable(); // id do banco
            $table->boolean('reconciled')->default(false);

            $table->timestamps();

            $table->index(['client_id', 'transaction_date']);
            $table->index('reconciled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_statements');
    }
};
