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
        // Azienda di esempio
        $company = Company::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
            'brand_color' => '#1d4ed8',
            'shared_passcode' => 'DEMO2024',
        ]);

        // Utente gestore
        $user = User::create([
            'name' => 'Admin Gestore',
            'email' => 'admin@acme.test',
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
    }
}
