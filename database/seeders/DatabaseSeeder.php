<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BankSeeder::class,
            PaymentMethodSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            CategorySeeder::class,
            CompanySeeder::class,
            PersonSeeder::class,
            StatusesSeeder::class,
        ]);
    }
}
