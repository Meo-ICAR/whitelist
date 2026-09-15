<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Conservazione dei dati (Data Retention)
    |--------------------------------------------------------------------------
    |
    | Numero di mesi trascorsi i quali una segnalazione CHIUSA viene
    | anonimizzata automaticamente (contenuto, allegati e messaggi
    | cancellati, mantenendo solo i metadati necessari per le statistiche
    | e l'audit). Adeguare al periodo previsto dalla normativa applicabile
    | (es. la Direttiva UE 2019/1937 lascia il termine alla legislazione
    | nazionale di recepimento).
    |
    */
    'retention_months' => env('REPORT_RETENTION_MONTHS', 24),
];
