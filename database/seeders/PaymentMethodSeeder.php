<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Dinheiro',
                'code' => 'cash',
                'description' => 'Pagamento em espécie',
                'active' => true,
                'requires_bank' => false,
                'requires_card' => false,
            ],
            [
                'name' => 'Cartão de Crédito',
                'code' => 'credit_card',
                'description' => 'Pagamento com cartão de crédito',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => true,
            ],
            [
                'name' => 'Cartão de Débito',
                'code' => 'debit_card',
                'description' => 'Pagamento com cartão de débito',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => true,
            ],
            [
                'name' => 'Transferência Bancária',
                'code' => 'bank_transfer',
                'description' => 'Transferência entre contas bancárias',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => false,
            ],
            [
                'name' => 'PIX',
                'code' => 'pix',
                'description' => 'Pagamento instantâneo via PIX',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => false,
            ],
            [
                'name' => 'Boleto',
                'code' => 'boleto',
                'description' => 'Pagamento via boleto bancário',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => false,
            ],
            [
                'name' => 'Cheque',
                'code' => 'check',
                'description' => 'Pagamento com cheque',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => false,
            ],
            [
                'name' => 'Débito em Conta',
                'code' => 'automatic_debit',
                'description' => 'Débito automático em conta',
                'active' => true,
                'requires_bank' => true,
                'requires_card' => false,
            ],
            [
                'name' => 'Crediário',
                'code' => 'store_credit',
                'description' => 'Pagamento parcelado no crediário da loja',
                'active' => true,
                'requires_bank' => false,
                'requires_card' => false,
            ],
            [
                'name' => 'Vale Alimentação/Refeição',
                'code' => 'meal_voucher',
                'description' => 'Pagamento com vale alimentação ou refeição',
                'active' => true,
                'requires_bank' => false,
                'requires_card' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info(count($paymentMethods) . ' métodos de pagamento criados.');
    }
}