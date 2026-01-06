<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\CreateSettings;
use App\Filament\Resources\Settings\Pages\EditSettings;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\Schemas\SettingsForm;
use App\Filament\Resources\Settings\Tables\SettingsTable;
use App\Models\Settings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
class SettingsResource extends Resource
{    
    protected static ?string $model = Settings::class;
  
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Settings';
      protected static bool $isSingleton = true;

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('cmpny_name')->required(),
            TextInput::make('cmpny_email')->email(),
            TextInput::make('cmpny_phone'),
            TextInput::make('cmpny_address')->required(),
            TextInput::make('cmpny_other'),
            TextInput::make('tax_name'),
            TextInput::make('tax_perc'),
            TextInput::make('set_currency'),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                TextColumn::make('cmpny_name')->searchable(),
                TextColumn::make('cmpny_email')->searchable(),
                TextColumn::make('cmpny_phone')->searchable(),
                  
            ])
             ->actions([
                 EditAction::make(),
                 DeleteAction::make(),
             ]);
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
        'index' => ListSettings::route('/'),
        'edit' => EditSettings::route('/{record}/edit'),
    ];
        
    }
}
