<?php

namespace Database\Seeders;

use App\Models\CorrectiveMeasureTemplate;
use Illuminate\Database\Seeder;

class CorrectiveMeasureTemplateSeeder extends Seeder
{
    /**
     * Modelli globali (company_id nullo): richiamabili da qualunque azienda,
     * non modificabili/eliminabili dal pannello. Coprono i casi più comuni
     * di misure correttive adottate a seguito di una segnalazione di
     * whistleblowing, conformi ai principi di proporzionalità del D.Lgs.
     * 24/2023 e alla tutela anti-ritorsione del segnalante.
     */
    public function run(): void
    {
        $templates = [
            [
                'title' => 'Richiamo verbale',
                'content' => "In data odierna è stato effettuato un richiamo verbale nei confronti del soggetto coinvolto, con contestazione informale dei fatti oggetto della segnalazione e invito a conformare la propria condotta alle policy aziendali.\n\nNessuna misura disciplinare formale è stata al momento adottata.",
            ],
            [
                'title' => 'Contestazione disciplinare formale',
                'content' => "È stata avviata la procedura di contestazione disciplinare formale nei confronti del soggetto coinvolto, ai sensi del CCNL applicabile e del codice disciplinare aziendale.\n\nAl soggetto è stato garantito il diritto di difesa mediante termine per presentare giustificazioni scritte.",
            ],
            [
                'title' => 'Sospensione cautelare',
                'content' => "Il soggetto coinvolto è stato sospeso cautelativamente dalle proprie funzioni in attesa dell'esito dell'istruttoria, al fine di prevenire il ripetersi della condotta oggetto di segnalazione e tutelare il segnalante da possibili ritorsioni.",
            ],
            [
                'title' => 'Revisione di processi e controlli interni',
                'content' => "A seguito della segnalazione sono state avviate le seguenti azioni correttive sui processi aziendali:\n- revisione della procedura interna coinvolta;\n- rafforzamento dei controlli di primo/secondo livello;\n- aggiornamento del registro dei rischi.\n\nÈ stata calendarizzata una verifica di efficacia entro i successivi 6 mesi.",
            ],
            [
                'title' => 'Formazione e sensibilizzazione del personale',
                'content' => "È stato pianificato un intervento formativo rivolto al personale coinvolto (e/o all'intera struttura interessata) sui temi oggetto della segnalazione, con l'obiettivo di prevenire il ripetersi di condotte analoghe.",
            ],
            [
                'title' => 'Segnalazione agli organi competenti (Modello 231 / Autorità)',
                'content' => "I fatti sono stati portati a conoscenza dell'Organismo di Vigilanza (OdV) ai sensi del Modello 231 adottato dall'azienda.\n\nOve rilevante, si è provveduto a informare le autorità competenti nei termini e modi previsti dalla normativa applicabile.",
            ],
            [
                'title' => 'Archiviazione per infondatezza',
                'content' => "All'esito dell'istruttoria condotta, i fatti oggetto della segnalazione non hanno trovato riscontro. La pratica viene pertanto archiviata senza l'adozione di misure correttive, fermo restando il divieto di qualsiasi forma di ritorsione nei confronti del segnalante.",
            ],
            [
                'title' => 'Misure a tutela del segnalante',
                'content' => "Sono state adottate le seguenti misure a tutela del segnalante contro possibili ritorsioni:\n- monitoraggio della posizione lavorativa del segnalante nei mesi successivi alla segnalazione;\n- canale diretto e riservato con il gestore per segnalare eventuali atti ritorsivi;\n- informativa ai responsabili di linea sul divieto di ritorsione previsto dalla legge.",
            ],
        ];

        foreach ($templates as $template) {
            CorrectiveMeasureTemplate::query()->firstOrCreate(
                ['company_id' => null, 'title' => $template['title']],
                ['content' => $template['content']],
            );
        }
    }
}
