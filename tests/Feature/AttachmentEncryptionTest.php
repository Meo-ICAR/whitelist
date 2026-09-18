<?php

namespace Tests\Feature;

use App\Livewire\PublicReportForm;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AttachmentEncryptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function uploaded_evidence_is_stored_encrypted_on_disk(): void
    {
        Storage::fake('private');

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        $originalContent = 'contenuto segreto della prova allegata';

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->set('data.title', 'Segnalazione con prova')
            ->set('data.description', 'Descrizione')
            ->set('data.attachments', [
                UploadedFile::fake()->createWithContent('prova.pdf', $originalContent)->mimeType('application/pdf'),
            ])
            ->call('submit');

        $report = Report::first();
        $media = $report->getFirstMedia('evidence');

        $this->assertNotNull($media);
        $this->assertTrue((bool) $media->getCustomProperty('encrypted'));

        $rawDiskContent = Storage::disk('private')->get($media->getPathRelativeToRoot());

        $this->assertStringNotContainsString($originalContent, $rawDiskContent);
    }

    /** @test */
    public function decrypted_download_returns_the_original_content_and_filename(): void
    {
        Storage::fake('private');

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $manager = User::factory()->create();
        $manager->companies()->attach($company);

        $originalContent = 'contenuto segreto della prova allegata';

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->set('data.title', 'Segnalazione con prova')
            ->set('data.description', 'Descrizione')
            ->set('data.attachments', [
                UploadedFile::fake()->createWithContent('prova.pdf', $originalContent)->mimeType('application/pdf'),
            ])
            ->call('submit');

        $report = Report::first();
        $media = $report->getFirstMedia('evidence');

        $response = $this->actingAs($manager)->get(route('media.download', $media));

        $response->assertOk();
        $this->assertSame($originalContent, $response->getContent());
        $response->assertHeader('Content-Disposition', 'attachment; filename="prova.pdf"');
    }

    /** @test */
    public function a_voice_recording_attachment_is_accepted_despite_its_x_wav_mime_type(): void
    {
        Storage::fake('private');

        // Regressione: il WAV prodotto dal registratore vocale in browser
        // (voice-recorder.js) viene rilevato lato server con fileinfo/
        // libmagic come "audio/x-wav", non "audio/wav": senza includere
        // anche questa variante in acceptedFileTypes(), l'upload veniva
        // sempre respinto con errore di validazione.
        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);

        Livewire::test(PublicReportForm::class, ['company' => $company])
            ->set('data.title', 'Segnalazione con messaggio vocale')
            ->set('data.description', 'Descrizione')
            ->set('data.attachments', [
                UploadedFile::fake()->create('messaggio-vocale.wav', 10)->mimeType('audio/x-wav'),
            ])
            ->call('submit')
            ->assertHasNoErrors(['data.attachments']);

        $report = Report::first();

        $this->assertNotNull($report->getFirstMedia('evidence'));
    }
}
