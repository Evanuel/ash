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
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->default(0)->comment('Client/ Tenant ID');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Parent Cost Center for hierarchy');

            $table->string('code')->comment('Code for sorting/display, e.g. 1.01');
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('status_id')->nullable()->constrained('statuses')->nullOnDelete();
            $table->boolean('active')->default(true);
            $table->string('color')->nullable()->comment('Color for UI');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('client_id');
            $table->index('parent_id');
            $table->index('code');

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cost_centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_centers');
    }
};
