<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('code')->nullable(false)->unique();
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();
            $table->boolean('requires_bank')->default(false);
            $table->boolean('requires_card')->default(false);

            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();

            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            
            $table->unique('name');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('payment_methods');
        Schema::enableForeignKeyConstraints();
        
    }
};