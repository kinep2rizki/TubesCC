<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['Login', 'Create Event', 'Generate Certificate', 'Register']),
            'description' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
