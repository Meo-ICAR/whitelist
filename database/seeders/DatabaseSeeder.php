<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Modelli globali di misure correttive, richiamabili da ogni azienda.
        $this->call(CorrectiveMeasureTemplateSeeder::class);

        // Azienda di esempio
        $company0 = Company::create([
            'name' => 'RACES Finance',
            'slug' => 'races',
            'brand_color' => '#1d4ed8',
            'shared_passcode' => 'DEMO2024',
        ]);

        // Utente gestore
        $user0 = User::create([
            'name' => 'Admin RACES',
            'email' => 'sergio.bracale@races.it',
            'password' => Hash::make('password'),
        ]);

        $user1 = User::create([
            'name' => 'Mario',
            'email' => 'mario.gargiulo@races.it',
            'password' => Hash::make('password'),
        ]);

        $company0->users()->attach($user0->id);
        $company0->users()->attach($user1->id);

        // Azienda di esempio
        $company = Company::create([
            'name' => 'Hassisto',
            'slug' => 'hassisto',
            'brand_color' => '#1d4ed8',
            'shared_passcode' => 'DEMO2024',
        ]);

        // Utente gestore
        $user = User::create([
            'name' => 'Admin Gestore',
            'email' => 'hassistosrl@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Assegna l'utente all'azienda tramite la pivot table
        $company->users()->attach($user->id);

        // Segnalazione di test
        $report = Report::create([
            'company_id' => $company->id,
            'tracking_token' => 'WHSL-DEMO-0001',
            'status' => 'new',
            'title' => 'Segnalazione di test',
            'description' => 'Questa è una segnalazione di esempio per testare la piattaforma.',
        ]);

        // Messaggio di risposta del gestore
        Message::create([
            'report_id' => $report->id,
            'body' => 'Grazie per la segnalazione. La stiamo esaminando.',
            'is_from_reporter' => false,
        ]);

        // Azienda demo pubblica: usata dalla welcome page commerciale
        // (route '/') per far navigare chi richiede una demo, senza
        // passcode così il portale è liberamente accessibile.
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
