<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Message;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EncryptionTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
        ]);
    }

    /** @test */
    public function report_description_is_encrypted_in_database(): void
    {
        $plainText = 'Questa è una descrizione sensibile della segnalazione.';

        $report = Report::create([
            'company_id' => $this->company->id,
            'tracking_token' => 'WHSL-TEST-0001',
            'status' => 'new',
            'title' => 'Test Report',
            'description' => $plainText,
        ]);

        // Query raw DB — bypasses Eloquent decryption
        $rawRow = DB::table('reports')->where('id', $report->id)->first();

        $this->assertNotEquals($plainText, $rawRow->description);
        $this->assertStringNotContainsString($plainText, $rawRow->description);
    }

    /** @test */
    public function report_description_is_decrypted_correctly_via_eloquent(): void
    {
        $plainText = 'Questa è una descrizione sensibile della segnalazione.';

        $report = Report::create([
            'company_id' => $this->company->id,
            'tracking_token' => 'WHSL-TEST-0002',
            'status' => 'new',
            'title' => 'Test Report',
            'description' => $plainText,
        ]);

        $fetched = Report::find($report->id);
        $this->assertEquals($plainText, $fetched->description);
    }

    /** @test */
    public function message_body_is_encrypted_in_database(): void
    {
        $report = Report::create([
            'company_id' => $this->company->id,
            'tracking_token' => 'WHSL-TEST-0003',
            'status' => 'new',
            'title' => 'Test Report',
            'description' => 'Some description',
        ]);

        $plainText = 'Questo è un messaggio riservato del gestore.';

        $message = Message::create([
            'report_id' => $report->id,
            'body' => $plainText,
            'is_from_reporter' => false,
        ]);

        // Query raw DB — bypasses Eloquent decryption
        $rawRow = DB::table('messages')->where('id', $message->id)->first();

        $this->assertNotEquals($plainText, $rawRow->body);
        $this->assertStringNotContainsString($plainText, $rawRow->body);
    }

    /** @test */
    public function message_body_is_decrypted_correctly_via_eloquent(): void
    {
        $report = Report::create([
            'company_id' => $this->company->id,
            'tracking_token' => 'WHSL-TEST-0004',
            'status' => 'new',
            'title' => 'Test Report',
            'description' => 'Some description',
        ]);

        $plainText = 'Questo è un messaggio riservato del gestore.';

        $message = Message::create([
            'report_id' => $report->id,
            'body' => $plainText,
            'is_from_reporter' => false,
        ]);

        $fetched = Message::find($message->id);
        $this->assertEquals($plainText, $fetched->body);
    }

    /** @test */
    public function encrypted_description_raw_value_looks_like_ciphertext(): void
    {
        $report = Report::create([
            'company_id' => $this->company->id,
            'tracking_token' => 'WHSL-TEST-0005',
            'status' => 'new',
            'title' => 'Test Report',
            'description' => 'Testo in chiaro da cifrare',
        ]);

        $rawRow = DB::table('reports')->where('id', $report->id)->first();

        // Laravel encrypted cast produces a base64-encoded ciphertext
        $this->assertNotEmpty($rawRow->description);
        $this->assertGreaterThan(20, strlen($rawRow->description));
    }
}
