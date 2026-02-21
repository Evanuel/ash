<?php

namespace Tests\Feature\Api\V1;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Role $adminRole;

    protected function setUp(): void
    {
        try {
            parent::setUp();

            // Criar role de super admin
            $this->adminRole = Role::factory()->superAdmin()->create(['client_id' => 1]);

            // Criar usuário admin
            $this->admin = User::factory()->create([
                'client_id' => 1,
                'role_id' => $this->adminRole->id
            ]);
        } catch (\Throwable $e) {
            echo "\nERROR IN SETUP: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
            throw $e;
        }
    }

    /**
     * Sobrescrever para evitar seeding pesado nos testes de Role.
     */
    protected function seedTestData(): void
    {
        // Pular seeding de cidades/estados/bancos para estes testes
        // Mas talvez precise de alguns tipos?
        try {
            if (!\App\Models\Type::exists()) {
                \App\Models\Type::create(['name' => 'Receita']);
                \App\Models\Type::create(['name' => 'Despesa']);
            }
        } catch (\Throwable $e) {
            echo "\nERROR IN SEED: " . $e->getMessage() . "\n";
        }
    }

    public function test_can_list_roles(): void
    {
        Role::factory()->count(3)->create(['client_id' => 1]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson('/api/v1/roles');

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data'); // 3 + 1 (admin role)
    }

    public function test_can_create_role(): void
    {
        $data = [
            'name' => 'Novo Cargo',
            'description' => 'Descrição do novo cargo',
            'level' => 50,
            'permissions' => ['financial.view', 'users.view'],
            'active' => true
        ];

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/roles', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Novo Cargo');

        $this->assertDatabaseHas('roles', [
            'name' => 'Novo Cargo',
            'client_id' => 1
        ]);
    }

    public function test_can_show_role(): void
    {
        $role = Role::factory()->create(['client_id' => 1]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->getJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $role->id);
    }

    public function test_can_update_role(): void
    {
        $role = Role::factory()->create(['client_id' => 1, 'name' => 'Old Name']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->putJson("/api/v1/roles/{$role->id}", [
                'name' => 'New Name'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'New Name');

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'New Name'
        ]);
    }

    public function test_can_delete_role(): void
    {
        $role = Role::factory()->create(['client_id' => 1]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('roles', ['id' => $role->id]);
    }

    public function test_can_restore_role(): void
    {
        $role = Role::factory()->create(['client_id' => 1]);
        $role->delete();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/roles/{$role->id}/restore");

        $response->assertStatus(200);
        $this->assertNotSoftDeleted('roles', ['id' => $role->id]);
    }
}
