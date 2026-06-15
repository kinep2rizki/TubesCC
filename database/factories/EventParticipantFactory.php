<?php

namespace Database\Factories;

use App\Models\EventParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;

/**
 * @extends Factory<EventParticipant>
 */
class EventParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'ticket_number' => strtoupper(fake()->unique()->bothify('TKT-####-????')),
            'status' => fake()->randomElement(['Registered', 'Attended', 'Cancelled']),
        ];
    }
}
