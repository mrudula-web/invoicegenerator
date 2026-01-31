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
  
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'Settings';
    protected static ?int $navigationSort = 7;
      protected static bool $isSingleton = true;
 public static function getGloballySearchableAttributes(): array
{
    return ['cmpny_name'];
}
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('cmpny_name')->label('Company Name')->required(),
            TextInput::make('cmpny_email')->label('Company Email')->email(),
            TextInput::make('cmpny_phone')->label('Company Phone'),
            TextInput::make('cmpny_address')->label('Company Address')->required(),
            TextInput::make('cmpny_other')->label('Company Other Details'),
            TextInput::make('tax_name')->label('Tax Name')->required(),
            TextInput::make('tax_perc')->label('Tax Percentage')->required(),
            TextInput::make('set_currency')->label('Currency')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                TextColumn::make('cmpny_name')->label('Company Name')->searchable(),
                TextColumn::make('cmpny_email')->label('Company Email')->searchable(),
                TextColumn::make('cmpny_phone')->label('Company Phone')->searchable(),

            ])
             ->actions([
                 EditAction::make(),
                // DeleteAction::make(),
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
