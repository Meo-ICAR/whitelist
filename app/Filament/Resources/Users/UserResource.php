<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Gestori Clienti';

    protected static ?string $modelLabel = 'Gestore';

    protected static ?string $pluralModelLabel = 'Gestori';

    protected static string|\UnitEnum|null $navigationGroup = 'Amministrazione SaaS';

    // Mettiamo questa risorsa sotto quella delle Aziende nel menu
    protected static ?int $navigationSort = 2;

    /**
     * Un gestore normale vede/gestisce solo i gestori della propria azienda
     * (comportamento di default di Filament, tramite la relazione "company"
     * su User). Un superadmin SaaS deve invece vedere e creare gestori per
     * qualunque azienda cliente.
     *
     * Vedi il commento su CompanyResource::getEloquentQuery() per il perché
     * questo va fatto qui e non sovrascrivendo isScopedToTenant().
     *
     * @return Builder<User>
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
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
