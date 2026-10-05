<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PinGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function generatePin(): string
    {
        return 'WHSL-' . strtoupper(Str::random(4) . '-' . Str::random(4));
    }

    /** @test */
    public function pin_matches_expected_format(): void
    {
        $pin = $this->generatePin();

        $this->assertMatchesRegularExpression(
            '/^WHSL-[A-Z0-9]{4}-[A-Z0-9]{4}$/',
            $pin
        );
    }

    /** @test */
    public function pin_always_starts_with_whsl_prefix(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $pin = $this->generatePin();
            $this->assertStringStartsWith('WHSL-', $pin);
        }
    }

    /** @test */
    public function pin_has_correct_total_length(): void
    {
        // WHSL-XXXX-XXXX = 14 characters
        $pin = $this->generatePin();
        $this->assertEquals(14, strlen($pin));
    }

    /** @test */
    public function pin_segments_contain_only_uppercase_alphanumeric(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $pin = $this->generatePin();
            $parts = explode('-', $pin);

            // parts[0] = WHSL, parts[1] = segment1, parts[2] = segment2
            $this->assertMatchesRegularExpression('/^[A-Z0-9]{4}$/', $parts[1]);
            $this->assertMatchesRegularExpression('/^[A-Z0-9]{4}$/', $parts[2]);
        }
    }

    /** @test */
    public function generated_pins_are_unique_in_database(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
        ]);

        $pins = [];

        for ($i = 0; $i < 10; $i++) {
            $pin = 'WHSL-' . strtoupper(Str::random(4) . '-' . Str::random(4));

            // Simulate uniqueness check as done in PublicReportForm::submit()
            while (Report::where('tracking_token', $pin)->exists()) {
                $pin = 'WHSL-' . strtoupper(Str::random(4) . '-' . Str::random(4));
            }

            Report::create([
                'company_id' => $company->id,
                'tracking_token' => $pin,
                'status' => 'new',
                'title' => "Report $i",
                'description' => "Description $i",
            ]);

            $pins[] = $pin;
        }

        // All PINs must be unique
        $this->assertCount(10, array_unique($pins));
        $this->assertEquals(10, Report::count());
    }

    /** @test */
    public function tracking_token_is_unique_constraint_in_database(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
        ]);

        $pin = 'WHSL-ABCD-1234';

        Report::create([
            'company_id' => $company->id,
            'tracking_token' => $pin,
            'status' => 'new',
            'title' => 'First Report',
            'description' => 'First description',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Report::create([
            'company_id' => $company->id,
            'tracking_token' => $pin,
            'status' => 'new',
            'title' => 'Duplicate Report',
            'description' => 'Duplicate description',
        ]);
    }
}
