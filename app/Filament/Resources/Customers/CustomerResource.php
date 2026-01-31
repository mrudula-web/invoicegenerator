<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
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



class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'Customers';
    protected static ?int $navigationSort = 1;
public static function getGloballySearchableAttributes(): array
{
    return ['name', 'email', 'phone_no_one'];
}
    public static function form(Schema $schema): Schema
    {
       return $schema
        ->components([
            TextInput::make('name')->label('Name')->required(),
            TextInput::make('company_name')->label('Company Name'),
            TextInput::make('email')->label('Email')->email(),
            TextInput::make('phone_no_one')->label('Phone Number One')->required(),
            TextInput::make('address')->label('Address'),
            TextInput::make('other_details')->label('Other Details'),

            
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
       return $table
            ->columns([
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('phone_no_one')->label('Phone Number')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                  
            ])
            ->filters([
                SelectFilter::make('email'),
                SelectFilter::make('name'),
                SelectFilter::make('phone_no_one'),
            ])
            ->actions([
                 EditAction::make(),
                 DeleteAction::make(),
            ])
            ->bulkActions([
                    BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
