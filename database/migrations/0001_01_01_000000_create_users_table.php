<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable(false)->unique();
            $table->unsignedBigInteger('client_id')->default(0);
            $table->string('name')->nullable(false);
            $table->string('email')->nullable(false)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            
            // Roles and permissions
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->json('permissions')->nullable()->comment('Additional user-specific permissions');
            
            // Hierarchy
            $table->unsignedBigInteger('branch_id')->default(0);
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('user_id')->default(0)->comment('Original user ID from legacy system');
            
            // References
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('people_id')->nullable();
            
            // Status
            $table->boolean('archived')->default(false);
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->timestamp('archived_at')->nullable();
            
            // Custom fields
            $table->string('custom_field1')->nullable();
            $table->string('custom_field2')->nullable();
            $table->string('custom_field3')->nullable();
            $table->text('notes')->nullable();
            
            // Profile
            $table->string('profile_image')->nullable();
            $table->boolean('active')->default(true);
            
            // Audit
            $table->unsignedBigInteger('created_by')->default(0);
            $table->unsignedBigInteger('updated_by')->nullable();
            
            // Laravel defaults
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('client_id');
            $table->index('branch_id');
            $table->index('supervisor_id');
            $table->index('active');
            $table->index('archived');
            $table->index('role_id');
            $table->index(['client_id', 'active']);
            $table->index('deleted_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
