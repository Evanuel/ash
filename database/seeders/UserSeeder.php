<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'client_id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@ash.elf.eng.br',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('@dmin#2026'),
                'role_id' => 1, // Super Admin ou Admin, dependendo do seu RolesSeeder
                'permissions' => json_encode(['*']),
                'branch_id' => 0,
                'supervisor_id' => null,
                'user_id' => 0,
                'company_id' => null,
                'people_id' => null,
                'archived' => false,
                'archived_by' => null,
                'archived_at' => null,
                'custom_field1' => null,
                'custom_field2' => null,
                'custom_field3' => null,
                'notes' => 'Usuário administrador padrão do sistema.',
                'profile_image' => null,
                'active' => true,
                'created_by' => 0,
                'updated_by' => null,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'username' => 'manager1',
                'client_id' => 1,
                'name' => 'Manager One',
                'email' => 'manager1@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('manager123'),
                'role_id' => 3, // Manager
                'permissions' => json_encode(['reports.view', 'tasks.manage']),
                'branch_id' => 1,
                'supervisor_id' => 1,
                'user_id' => 0,
                'company_id' => null,
                'people_id' => null,
                'archived' => false,
                'archived_by' => null,
                'archived_at' => null,
                'custom_field1' => null,
                'custom_field2' => null,
                'custom_field3' => null,
                'notes' => null,
                'profile_image' => null,
                'active' => true,
                'created_by' => 1,
                'updated_by' => null,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'username' => 'user1',
                'client_id' => 1,
                'name' => 'Basic User',
                'email' => 'user1@example.com',
                'email_verified_at' => null,
                'password' => Hash::make('user123'),
                'role_id' => 4, // User
                'permissions' => json_encode(['profile.view']),
                'branch_id' => 1,
                'supervisor_id' => 2,
                'user_id' => 0,
                'company_id' => null,
                'people_id' => null,
                'archived' => false,
                'archived_by' => null,
                'archived_at' => null,
                'custom_field1' => null,
                'custom_field2' => null,
                'custom_field3' => null,
                'notes' => null,
                'profile_image' => null,
                'active' => true,
                'created_by' => 1,
                'updated_by' => null,
                'remember_token' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
