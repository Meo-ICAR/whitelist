<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use App\Filament\Resources\Companies\Tables\CompaniesTable;
use App\Models\Company;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Aziende Clienti';

    protected static ?string $modelLabel = 'Azienda';

    protected static ?string $pluralModelLabel = 'Aziende';

    protected static string|UnitEnum|null $navigationGroup = 'Amministrazione SaaS';

    /**
     * Un gestore normale vede/modifica solo l'azienda del tenant corrente
     * (comportamento di default di Filament, dato che il modello di questa
     * risorsa coincide col modello tenant). Un superadmin SaaS deve invece
     * vedere ed inserire tutte le aziende clienti, indipendentemente dal
     * tenant selezionato.
     *
     * Bugfix: sovrascrivere isScopedToTenant() invece di getEloquentQuery()
     * è pericoloso qui, perché Filament decide UNA VOLTA SOLA (al boot del
     * pannello) se registrare lo scope globale di tenancy sul modello
     * Company, leggendo isScopedToTenant() in quel momento. In un worker
     * PHP-FPM di lunga durata, se quella prima valutazione avviene per un
     * superadmin lo scope non viene mai registrato per NESSUNA richiesta
     * successiva gestita dallo stesso worker, nemmeno per i gestori normali.
     * getEloquentQuery() invece viene rivalutato ad ogni richiesta, quindi è
     * sicuro rimuovere lo scope qui, solo quando serve.
     *
     * @return Builder<Company>
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->is_superadmin) {
            $query->withoutGlobalScope(Filament::getCurrentOrDefaultPanel()->getTenancyScopeName());
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompaniesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }
}
