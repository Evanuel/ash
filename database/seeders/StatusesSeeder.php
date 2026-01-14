<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Statuses para transações financeiras (account)
        $statuses = [
            // Status para client_id = 1 (cliente principal)
            [
                'client_id' => 1,
                'name' => 'Pendente',
                'description' => 'Transação aguardando pagamento',
                'color' => '#FF9800',
                'icon' => 'clock',
                'color_class' => 'text-warning',
                'text_class' => 'text-warning',
                'bg_class' => 'bg-warning-subtle',
                'type' => 'account',
                'order' => 1,
                'is_default' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Pago',
                'description' => 'Transação paga',
                'color' => '#4CAF50',
                'icon' => 'check-circle',
                'color_class' => 'text-success',
                'text_class' => 'text-success',
                'bg_class' => 'bg-success-subtle',
                'type' => 'account',
                'order' => 2,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Atrasado',
                'description' => 'Transação em atraso',
                'color' => '#F44336',
                'icon' => 'exclamation-triangle',
                'color_class' => 'text-danger',
                'text_class' => 'text-danger',
                'bg_class' => 'bg-danger-subtle',
                'type' => 'account',
                'order' => 3,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Cancelado',
                'description' => 'Transação cancelada',
                'color' => '#9E9E9E',
                'icon' => 'x-circle',
                'color_class' => 'text-secondary',
                'text_class' => 'text-secondary',
                'bg_class' => 'bg-secondary-subtle',
                'type' => 'account',
                'order' => 4,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Agendado',
                'description' => 'Pagamento agendado',
                'color' => '#2196F3',
                'icon' => 'calendar',
                'color_class' => 'text-info',
                'text_class' => 'text-info',
                'bg_class' => 'bg-info-subtle',
                'type' => 'account',
                'order' => 5,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Parcialmente Pago',
                'description' => 'Pagamento parcial realizado',
                'color' => '#FFC107',
                'icon' => 'percent',
                'color_class' => 'text-warning',
                'text_class' => 'text-warning',
                'bg_class' => 'bg-warning-subtle',
                'type' => 'account',
                'order' => 6,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            
            // Status para outros tipos (opcional)
            [
                'client_id' => 1,
                'name' => 'Ativo',
                'description' => 'Cliente ativo',
                'color' => '#4CAF50',
                'icon' => 'user-check',
                'color_class' => 'text-success',
                'text_class' => 'text-success',
                'bg_class' => 'bg-success-subtle',
                'type' => 'client',
                'order' => 1,
                'is_default' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Inativo',
                'description' => 'Cliente inativo',
                'color' => '#9E9E9E',
                'icon' => 'user-x',
                'color_class' => 'text-secondary',
                'text_class' => 'text-secondary',
                'bg_class' => 'bg-secondary-subtle',
                'type' => 'client',
                'order' => 2,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            
            // Status para pedidos (order)
            [
                'client_id' => 1,
                'name' => 'Novo',
                'description' => 'Pedido recebido',
                'color' => '#2196F3',
                'icon' => 'inbox',
                'color_class' => 'text-primary',
                'text_class' => 'text-primary',
                'bg_class' => 'bg-primary-subtle',
                'type' => 'order',
                'order' => 1,
                'is_default' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Em Processamento',
                'description' => 'Pedido sendo preparado',
                'color' => '#FF9800',
                'icon' => 'package',
                'color_class' => 'text-warning',
                'text_class' => 'text-warning',
                'bg_class' => 'bg-warning-subtle',
                'type' => 'order',
                'order' => 2,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => 1,
                'name' => 'Concluído',
                'description' => 'Pedido entregue',
                'color' => '#4CAF50',
                'icon' => 'check-circle',
                'color_class' => 'text-success',
                'text_class' => 'text-success',
                'bg_class' => 'bg-success-subtle',
                'type' => 'order',
                'order' => 3,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            
            // Status global (client_id = null) - para uso do sistema
            [
                'client_id' => null,
                'name' => 'Ativo',
                'description' => 'Status ativo padrão',
                'color' => '#4CAF50',
                'icon' => 'check',
                'color_class' => 'text-success',
                'text_class' => 'text-success',
                'bg_class' => 'bg-success-subtle',
                'type' => 'system',
                'order' => 1,
                'is_default' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'client_id' => null,
                'name' => 'Inativo',
                'description' => 'Status inativo padrão',
                'color' => '#9E9E9E',
                'icon' => 'x',
                'color_class' => 'text-secondary',
                'text_class' => 'text-secondary',
                'bg_class' => 'bg-secondary-subtle',
                'type' => 'system',
                'order' => 2,
                'is_default' => false,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        // Inserir os statuses
        foreach ($statuses as $status) {
            DB::table('statuses')->updateOrInsert(
                [
                    'client_id' => $status['client_id'],
                    'type' => $status['type'],
                    'name' => $status['name'],
                ],
                $status
            );
        }

        $this->command->info('✅ Statuses seeded successfully!');
        $this->command->info('📊 Status types: account, client, order, system');
        $this->command->info('🎨 Colors: Success (green), Warning (orange), Danger (red), Info (blue), Secondary (gray)');
    }
}