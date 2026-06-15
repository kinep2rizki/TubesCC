<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventParticipant;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
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
            'template_style' => fake()->randomElement(['modern', 'classic', 'minimalist']),
            'file_url' => fake()->url(),
            'issued_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
