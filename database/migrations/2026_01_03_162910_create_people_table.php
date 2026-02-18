<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');
            $table->tinyInteger('type')->default(1);
            $table->string('cpf', 14)->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('rg')->nullable();

            // Address
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('city_id')->nullable()->constrained('cities');

            // Personal details
            $table->date('birthdate')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('subcategory_id')->nullable()->constrained('categories');

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

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

            // Indexes
            $table->unique(['client_id', 'cpf']);
            $table->index(['first_name', 'last_name']);
            $table->index('status');
            $table->index('archived');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
};