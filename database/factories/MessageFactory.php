<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'body' => fake()->paragraph(),
            'is_from_reporter' => fake()->boolean(),
        ];
    }
}
