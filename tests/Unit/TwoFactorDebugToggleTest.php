<?php

namespace Tests\Unit;

use Filament\Facades\Filament;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifica diretta e deterministica di AdminPanelProvider: la 2FA è
 * obbligatoria quando APP_DEBUG è false, opzionale quando è true.
 *
 * Ogni metodo gira in un processo PHP separato e imposta la variabile
 * d'ambiente PRIMA che l'applicazione venga avviata (nel setUp() ereditato),
 * esattamente come accade in un vero avvio del server: Illuminate\Support\Env
 * cachea staticamente il proprio repository al primo boot, quindi un secondo
 * boot nello stesso processo non rilegge l'ambiente aggiornato.
 */
class TwoFactorDebugToggleTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function two_factor_is_required_when_debug_is_off(): void
    {
        putenv('APP_DEBUG=false');
        $_ENV['APP_DEBUG'] = 'false';
        $_SERVER['APP_DEBUG'] = 'false';

        parent::setUp();

        $this->assertFalse(config('app.debug'));
        $this->assertTrue(Filament::getPanel('admin')->isMultiFactorAuthenticationRequired());
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function two_factor_is_optional_when_debug_is_on(): void
    {
        putenv('APP_DEBUG=true');
        $_ENV['APP_DEBUG'] = 'true';
        $_SERVER['APP_DEBUG'] = 'true';

        parent::setUp();

        $this->assertTrue(config('app.debug'));
        $this->assertFalse(Filament::getPanel('admin')->isMultiFactorAuthenticationRequired());
    }

    protected function setUp(): void
    {
        // Intenzionalmente vuoto: ogni test chiama parent::setUp() da sé,
        // dopo aver impostato APP_DEBUG, per garantire che l'app venga
        // avviata una sola volta con il valore corretto già in ambiente.
    }
}
