<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiscal_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable(false)->comment('Cliente/Tenant');

            // Classification
            $table->foreignId('type_id')->nullable()->constrained('types')->comment('Tipo de documento (NF-e, NFS-e, etc)');
            $table->foreignId('status_id')->nullable()->constrained('statuses')->comment('Status do documento');
            $table->tinyInteger('direction')->default(1)->comment('1 = Entrada (Inbound), 2 = Saída (Outbound)');

            // Document Identification
            $table->string('access_key', 44)->nullable()->comment('Chave de acesso (44 dígitos)');
            $table->string('number')->nullable()->comment('Número do documento');
            $table->string('series')->nullable()->comment('Série');
            $table->string('model')->nullable()->comment('Modelo (ex: 55, 65)');

            // Issuance and Verification
            $table->date('issue_date')->nullable()->comment('Data de emissão');
            $table->string('verification_code')->nullable()->comment('Código de verificação (NFS-e)');

            // Parties
            $table->string('issuer_name')->nullable()->comment('Nome do emitente');
            $table->string('issuer_tax_id')->nullable()->comment('CNPJ/CPF do emitente');
            $table->string('recipient_name')->nullable()->comment('Nome do destinatário');
            $table->string('recipient_tax_id')->nullable()->comment('CNPJ/CPF do destinatário');

            // Financial Data
            $table->decimal('gross_amount', 15, 2)->nullable()->comment('Valor bruto');
            $table->decimal('net_amount', 15, 2)->nullable()->comment('Valor líquido');
            $table->decimal('tax_amount', 15, 2)->nullable()->comment('Valor total dos impostos');
            $table->json('tax_details')->nullable()->comment('Detalhamento de impostos (ICMS, IPI, PIS, COFINS, ISS)');

            // File Storage
            $table->string('xml_url')->nullable()->comment('URL do arquivo XML');
            $table->string('pdf_url')->nullable()->comment('URL do arquivo PDF/DANFE');

            // Accounting and Export
            $table->timestamp('accounting_exported_at')->nullable()->comment('Data da última exportação contábil');

            // Audit and Archival
            $table->unsignedBigInteger('created_by')->default(0)->comment('Usuário que criou');
            $table->unsignedBigInteger('updated_by')->default(0)->comment('Usuário que atualizou');
            $table->boolean('archived')->default(false)->comment('Arquivado');
            $table->unsignedBigInteger('archived_by')->nullable()->comment('Usuário que arquivou');
            $table->timestamp('archived_at')->nullable()->comment('Data de arquivamento');

            // Standard Fields
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('client_id');
            $table->index('type_id');
            $table->index('status_id');
            $table->index('access_key');
            $table->index('number');
            $table->index('issue_date');
            $table->index('archived');
            $table->index(['client_id', 'issue_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_documents');
    }
};
