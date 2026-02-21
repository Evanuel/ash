<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => 0,
            'name' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->sentence(),
            'level' => $this->faker->numberBetween(0, 80),
            'permissions' => [],
            'active' => true,
        ];
    }

    /**
     * Role de admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'level' => 90,
            'name' => 'Administrator',
            'permissions' => ['*'],
        ]);
    }

    /**
     * Role de super admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'level' => 100,
            'name' => 'Super Administrator',
            'permissions' => ['*'],
        ]);
    }
}
