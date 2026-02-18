<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->nullable(false);
            $table->string('name', 40)->nullable(false);
            $table->string('uf', 2)->nullable(false);
            $table->integer('state_code')->nullable(false);
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->unique('code');
            $table->index('name');
            $table->index(['uf', 'name']);
            $table->index('state_code');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('cities');
        Schema::enableForeignKeyConstraints();
    }
};