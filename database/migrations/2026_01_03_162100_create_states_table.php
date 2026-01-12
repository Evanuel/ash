<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->nullable(false)->comment('IBGE state code');
            $table->string('name')->nullable(false);
            $table->string('uf', 2)->nullable(false);
            
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->unique('code');
            $table->unique('uf');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('states');
        Schema::enableForeignKeyConstraints();
    }
};