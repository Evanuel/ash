<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable();
            $table->integer('level')->default(0)->comment('Hierarchy level');
            $table->json('permissions')->nullable()->comment('Permissions in JSON format');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('client_id')->nullable()->comment('If role is client-specific');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['client_id', 'name']);
            $table->index('level');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};