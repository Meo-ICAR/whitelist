<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    // Precompila le credenziali demo di Acme Srl quando si arriva dal
    // pulsante "Accedi al pannello gestori" della welcome page commerciale
    // (link con ?demo=acme), così un potenziale acquirente non deve
    // copiare/incollare email e password a mano. Non compila nulla per
    // nessun altro valore del parametro, quindi non può essere usato per
    // precompilare credenziali arbitrarie.
    public function mount(): void
    {
        parent::mount();

        if (request()->query('demo') === 'acme') {
            $this->form->fill([
                'email' => 'demo@acme-demo.test',
                'password' => 'demo12345',
            ]);
        }
    }
}
