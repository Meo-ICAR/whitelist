<?php

namespace App\Filament\Resources\CorrectiveMeasureTemplates;

use App\Filament\Resources\CorrectiveMeasureTemplates\Pages\CreateCorrectiveMeasureTemplate;
use App\Filament\Resources\CorrectiveMeasureTemplates\Pages\EditCorrectiveMeasureTemplate;
use App\Filament\Resources\CorrectiveMeasureTemplates\Pages\ListCorrectiveMeasureTemplates;
use App\Filament\Resources\CorrectiveMeasureTemplates\Schemas\CorrectiveMeasureTemplateForm;
use App\Filament\Resources\CorrectiveMeasureTemplates\Tables\CorrectiveMeasureTemplatesTable;
use App\Models\CorrectiveMeasureTemplate;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CorrectiveMeasureTemplateResource extends Resource
{
    protected static ?string $model = CorrectiveMeasureTemplate::class;

    // Disabilitato: lo scoping automatico di Filament esclude le righe con
    // company_id nullo (i modelli globali), che invece vogliamo mostrare a
    // tutti. Lo scoping viene ricostruito a mano in getEloquentQuery().
    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Modelli Misure Correttive';

    protected static ?string $modelLabel = 'Modello di Misura Correttiva';

    protected static ?string $pluralModelLabel = 'Modelli di Misure Correttive';

    protected static string|UnitEnum|null $navigationGroup = 'Gestione Segnalazioni';

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant();

        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($tenant) {
                $query->whereNull('company_id')
                    ->orWhere('company_id', $tenant?->id);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return CorrectiveMeasureTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CorrectiveMeasureTemplatesTable::configure($table);
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
            'index' => ListCorrectiveMeasureTemplates::route('/'),
            'create' => CreateCorrectiveMeasureTemplate::route('/create'),
            'edit' => EditCorrectiveMeasureTemplate::route('/{record}/edit'),
        ];
    }
}
