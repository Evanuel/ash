<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            // Bank identification
            $table->string('code', 10)->nullable(false)->comment('Bank code (e.g., 001 for Banco do Brasil)');
            $table->string('ispb', 8)->nullable(false)->comment('ISPB code (8 digits)');
            $table->string('name')->nullable(false);
            $table->string('short_name')->nullable()->comment('Short name or abbreviation');
            $table->string('compe_code', 3)->nullable()->comment('COMPE code');

            // Bank details
            $table->string('document_number', 20)->nullable()->comment('CNPJ of the bank');
            $table->string('url')->nullable()->comment('Bank website');
            $table->string('logo')->nullable()->comment('URL or path to bank logo');

            // Bank type and classification
            $table->string('type')->default('commercial')->comment('commercial, investment, development, etc');
            $table->boolean('is_public')->default(false)->comment('If it is a public bank');
            $table->boolean('is_foreign')->default(false)->comment('If it is a foreign bank');

            // Status and availability
            $table->boolean('active')->default(true);
            $table->boolean('participates_on_pix')->default(true)->comment('If the bank participates in PIX system');
            $table->date('start_date')->nullable()->comment('When the bank started operations');
            $table->date('end_date')->nullable()->comment('When the bank ended operations (if applicable)');

            // Contact information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Address (optional)
            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_complement')->nullable();
            $table->string('address_neighborhood')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state', 2)->nullable();
            $table->string('address_zip_code', 9)->nullable();

            // Audit
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            // $table->unique(['client_id', 'code'], 'unique_bank_code');
            $table->unique('ispb');
            $table->index('code');
            $table->index('name');
            $table->index('active');
            $table->index('type');
            // $table->index('client_id');
            $table->index('archived');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('banks');
        Schema::enableForeignKeyConstraints();
    }
};