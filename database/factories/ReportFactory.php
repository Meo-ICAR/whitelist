<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        $segment1 = strtoupper(Str::random(4));
        $segment2 = strtoupper(Str::random(4));

        return [
            'company_id' => Company::factory(),
            'tracking_token' => "WHSL-{$segment1}-{$segment2}",
            'status' => fake()->randomElement(['new', 'in_progress', 'closed']),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
        ];
    }
}
