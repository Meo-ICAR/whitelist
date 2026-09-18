<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Company;
use App\Notifications\WebmasterInfo;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use UnitEnum;

class TestPratico extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';

    protected static ?string $navigationLabel = 'Test Pratico';

    protected static ?string $title = 'Test Pratico';

    protected static string|UnitEnum|null $navigationGroup = 'Documentazione';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.pages.test-pratico';

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        /** @var Company $company */
        $company = Filament::getTenant();

        return [
            Action::make('sendWebmasterInfo')
                ->label('Invia info al Webmaster')
                ->icon('heroicon-o-paper-airplane')
                ->color('gray')
                ->disabled(fn (): bool => blank($company->webmaster_email))
                ->tooltip(fn (): ?string => blank($company->webmaster_email)
                    ? "Imposta prima l'Email Webmaster (Configura l'azienda)"
                    : null)
                ->requiresConfirmation()
                ->modalDescription(fn (): string => "Invia a {$company->webmaster_email} il link pubblico, l'eventuale codice d'accesso, il QR code (allegato PNG) e gli snippet HTML pronti per il sito di {$company->name}.")
                ->action(function () use ($company): void {
                    LaravelNotification::route('mail', $company->webmaster_email)
                        ->notify(new WebmasterInfo($company));

                    FilamentNotification::make()
                        ->title('Email inviata al webmaster')
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * @return array{company: Company, editCompanyUrl: string, reportsUrl: string, publicFormUrl: string, trackerUrl: string, guideUrl: string}
     */
    protected function getViewData(): array
    {
        /** @var Company $company */
        $company = Filament::getTenant();

        return [
            'company' => $company,
            'editCompanyUrl' => CompanyResource::getUrl('edit', ['record' => $company]),
            'reportsUrl' => ReportResource::getUrl('index'),
            'publicFormUrl' => route('report.welcome', $company->slug),
            'trackerUrl' => route('report.track', $company->slug),
            'guideUrl' => route('report.guide', $company->slug),
        ];
    }
}
