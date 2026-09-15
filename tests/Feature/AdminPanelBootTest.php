<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminPanelBootTest extends TestCase
{
    /** @test */
    public function the_admin_login_page_boots_without_errors(): void
    {
        // Regressione: ->colors(['primary' => fn () => ...]) con la closure
        // su un singolo valore (anziché sull'intero array) manda in errore
        // fatale ColorManager su ogni pagina del pannello Filament.
        $response = $this->get('/admin/login');

        $response->assertOk();
    }
}
