<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Rodar seeders necessários para testes
        $this->seedTestData();
        
        // Configurar Sanctum para testes
        \Laravel\Sanctum\Sanctum::actingAs(
            \App\Models\User::factory()->create(),
            ['*']
        );
    }

    /**
     * Seed dados de teste.
     */
    protected function seedTestData(): void
    {
        // Seeders básicos que não dependem de client_id
        $this->seed([
            \Database\Seeders\BankSeeder::class,
            \Database\Seeders\PaymentMethodSeeder::class,
            \Database\Seeders\StateSeeder::class,
            \Database\Seeders\CitySeeder::class,
            \Database\Seeders\StatusesSeeder::class,
        ]);
        
        // Criar um tipo básico se não existir
        if (!\App\Models\Type::exists()) {
            \App\Models\Type::create(['name' => 'Receita']);
            \App\Models\Type::create(['name' => 'Despesa']);
        }
    }

    /**
     * Helper para criar um usuário com role.
     */
    protected function createUserWithRole($roleName = 'admin', $attributes = [])
    {
        // Criar ou obter role
        $role = \App\Models\Role::firstOrCreate(
            ['name' => $roleName],
            ['level' => $roleName === 'admin' ? 100 : 10]
        );
        
        // Criar usuário
        $user = \App\Models\User::factory()->create($attributes);
        $user->role()->associate($role);
        $user->save();
        
        return $user;
    }

    /**
     * Helper para criar dados relacionados para testes.
     */
    protected function createRelatedTestData($userId = null)
    {
        if (!$userId) {
            $user = \App\Models\User::first();
            $userId = $user->id;
        }
        
        // Criar client_id se não existir
        // $client = \App\Models\Client::firstOrCreate(
        //     ['id' => 1],
        //     ['name' => 'Test Client', 'active' => true]
        // );
        
        // Associar usuário ao client
        // $user = \App\Models\User::find($userId);
        // $user->client()->associate($client);
        // $user->save();
        
        // return [
        //     'client_id' => $client->id,
        //     'user' => $user,
        //     'client' => $client,
        // ];
    }
}