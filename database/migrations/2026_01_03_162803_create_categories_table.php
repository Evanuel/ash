<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('general')->comment('account, product, client, etc');
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('client_id')->nullable()->comment('If category is client-specific');
            $table->json('metadata')->nullable();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Self-referencing foreign key for hierarchical structure
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            
            $table->index(['type', 'active']);
            $table->index(['client_id', 'type']);
            $table->unique(['client_id', 'type', 'name', 'parent_id'], 'unique_category');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('categories');
        Schema::enableForeignKeyConstraints();
    }
};