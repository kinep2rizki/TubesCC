<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventParticipant;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_participant_id' => EventParticipant::factory(),
            'check_in_time' => fake()->dateTimeBetween('-1 month', 'now'),
            'check_in_method' => fake()->randomElement(['QR Code', 'Manual', 'NFC']),
        ];
    }
}
