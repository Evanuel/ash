<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');

            $table->unsignedBigInteger('type')->default(1);
            $table->string('cnpj', 18)->nullable(false);
            $table->string('trade_name')->nullable(false);
            $table->string('company_name')->nullable(false);
            $table->string('state_registration')->default('');
            $table->string('municipal_registration')->default('');

            // Address
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('city_id')->nullable()->constrained('cities');

            // Company details
            $table->string('logo')->nullable();
            $table->string('cnae')->default('');
            $table->date('opening_date')->nullable();
            $table->boolean('is_headquarters')->nullable();
            $table->integer('headquarters_code')->nullable();
            $table->boolean('is_branch')->nullable();
            $table->integer('branch_code')->nullable();

            // Categories
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('subcategory_id')->nullable()->constrained('categories');

            // Tax regime
            $table->tinyInteger('tax_regime')->default(1);

            // Contacts
            $table->json('contacts')->nullable()->comment('Structured contact information');

            // Financial
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('used_credit', 15, 2)->default(0);

            // Status
            $table->boolean('activated')->default(false);
            $table->tinyInteger('situation')->default(1);
            $table->boolean('status')->default(true);

            // Custom fields
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->text('notes')->nullable();

            // Audit
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->softDeletes();

            // Indexes
            $table->unique(['client_id', 'cnpj']);
            $table->index('trade_name');
            $table->index('company_name');
            $table->index('status');
            $table->index('archived');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('companies');
        Schema::enableForeignKeyConstraints();
    }
};