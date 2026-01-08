<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'description' => 'Full system access',
                'level' => 100,
                'permissions' => json_encode(['*']), // todas permissões
                'active' => true,
                'client_id' => null, // global
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Admin',
                'description' => 'Administrative access for a client',
                'level' => 80,
                'permissions' => json_encode([
                    'users.create',
                    'users.update',
                    'users.delete',
                    'roles.view',
                    'clients.manage'
                ]),
                'active' => true,
                'client_id' => 1, // específico do cliente 1
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Manager',
                'description' => 'Manages operational tasks',
                'level' => 50,
                'permissions' => json_encode([
                    'users.view',
                    'reports.view',
                    'tasks.manage'
                ]),
                'active' => true,
                'client_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User',
                'description' => 'Basic system access',
                'level' => 10,
                'permissions' => json_encode([
                    'profile.view',
                    'profile.update'
                ]),
                'active' => true,
                'client_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

}
