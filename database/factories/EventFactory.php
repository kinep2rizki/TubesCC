<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Community;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 4) . ' hours');

        return [
            'community_id' => Community::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->address(),
            'status' => fake()->randomElement(['Draft', 'Published', 'Completed']),
        ];
    }
}
