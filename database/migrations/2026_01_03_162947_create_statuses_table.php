<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('color_class')->nullable();
            $table->string('text_class')->nullable();
            $table->string('bg_class')->nullable();
            $table->string('type')->default('account')->comment('account, client, order, etc');
            $table->integer('order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('updated_by')->default(0);
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'type']);
            $table->unique(['client_id', 'type', 'name'], 'unique_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};