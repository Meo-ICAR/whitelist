<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcmeDemoCompanySeeder extends Seeder
{
    /**
     * Azienda demo pubblica (Acme Srl): usata dalla welcome page commerciale
     * (route '/') per far navigare chi richiede una demo, senza passcode così
     * il portale è liberamente accessibile. Interamente idempotente (usa
     * firstOrCreate ovunque) così può essere eseguito da solo anche in
     * ambienti dove esistono già altri dati, con:
     * php artisan db:seed --class="Database\Seeders\AcmeDemoCompanySeeder"
     */
    public function run(): void
    {
        $acme = Company::firstOrCreate(
            ['slug' => 'acme'],
            [
                'name' => 'Acme Srl',
                'brand_color' => '#0f766e',
                'shared_passcode' => null,
                'webmaster_email' => 'webmaster@acme-demo.test',
            ]
        );

        $acmeManager = User::firstOrCreate(
            ['email' => 'demo@acme-demo.test'],
            [
                'name' => 'Demo Gestore Acme',
                'password' => Hash::make('demo12345'),
            ]
        );

        $acme->users()->syncWithoutDetaching([$acmeManager->id]);

        $acmeReport = Report::firstOrCreate(
            ['tracking_token' => 'WHSL-ACME-DEMO'],
            [
                'company_id' => $acme->id,
                'status' => 'in_progress',
                'title' => 'Irregolarità nella gestione fornitori',
                'description' => 'Segnalazione dimostrativa: presunta violazione della procedura di selezione fornitori nel reparto acquisti.',
            ]
        );

        Message::firstOrCreate(
            ['report_id' => $acmeReport->id, 'is_from_reporter' => false],
            ['body' => 'Grazie per la segnalazione. Il team compliance ha aperto un fascicolo e la terrà aggiornata su questo canale.']
        );
    }
}
