<?php

namespace App\Filament\Resources\Receipts;

use App\Filament\Resources\Receipts\Pages\CreateReceipt;
use App\Filament\Resources\Receipts\Pages\EditReceipt;
use App\Filament\Resources\Receipts\Pages\ListReceipts;
use App\Filament\Resources\Receipts\Pages\ViewReceipt;
use App\Filament\Resources\Receipts\Schemas\ReceiptForm;
use App\Filament\Resources\Receipts\Schemas\ReceiptInfolist;
use App\Filament\Resources\Receipts\Tables\ReceiptsTable;
use App\Models\Receipt;
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
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Customer;
use Filament\Forms\Components\Hidden;

class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $recordTitleAttribute = 'Receipt';
    protected static ?int $navigationSort = 5;
 public static function getGloballySearchableAttributes(): array
{
    return ['inv_num'];
}
    public static function form(Schema $schema): Schema
    {    
    
    $inv = Invoice::pluck('inv_no', 'id');
    $cust = Customer::pluck('name', 'id');
         return $schema
        ->components([
            Select::make('inv_num')->options($inv)->label('Invoice Number')->required()
             ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                // $state = selected invoice ID
                if ($state) {
                    $invoice = Invoice::find($state);

                    // Set invoice total
                    $set('inv_amount', $invoice?->inv_total ?? 0);
                      // Load customer id
                    $set('cust_id', $invoice?->cust_id);

                    // Load customer name for display
                    $set('cust_name_display', $invoice?->customer?->name);

                }
            }),
            TextInput::make('inv_amount')->label('Invoice Amount')->disabled()
              ->afterStateHydrated(function (callable $set, callable $get) {
        $invoiceId = $get('inv_num');

        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            $set('inv_amount', $invoice?->inv_total ?? 0);
        }
    }),
          TextInput::make('cust_name_display')
            ->label('Customer Name')
            ->disabled()
            ->afterStateHydrated(function (callable $set, callable $get) {
                if ($invoiceId = $get('inv_num')) {
                    $invoice = Invoice::find($invoiceId);
                    $set('cust_name_display', $invoice?->customer?->name);
                }
            }),

        // REAL CUSTOMER ID (SAVED)
        Hidden::make('cust_id'),
TextInput::make('balance')->label('Balance Amount')->disabled()
              ->afterStateHydrated(function (callable $set, callable $get) {
        $invoiceId = $get('inv_num');
        $receivedAmount = Receipt::where('inv_num', $invoiceId)->sum('rec_amount');
        $invoice = Invoice::find($invoiceId);
        $balance = $invoice?->inv_total - $receivedAmount;
        $set('balance', $balance);
    }),
            TextInput::make('rec_amount')->label('Received Amount')->required()
            ->rules(function (callable $get) {
        $balance = $get('balance') ?? 0;
        $invAmount = $get('inv_amount') ?? 0;

        return [
            "lte:$balance",      // must not exceed balance
            "lte:$invAmount",    // must not exceed invoice amount
            "gte:0",             // must be >= 0
        ];
    })
    ->validationMessages([
        'lte' => 'Received amount cannot exceed the Balance/Invoice Amount.',
        'gte' => 'Amount must be greater than or equal to 0.',
    ]),
            Select::make('rec_status')->label('Receipt Status')->options([
                'paid' => 'Paid',
                'partial' => 'Partially Paid',
            ])->required(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReceiptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.inv_no')->label('Invoice Number')->searchable(),
                TextColumn::make('rec_amount')->label('Receipt Amount'),
                TextColumn::make('rec_status')->label('Receipt Status')->searchable()
                 ->formatStateUsing(function ($state) {
        return match ($state) {
            'paid' => 'Paid',
            'partial' => 'Partially Paid',
            default => ucfirst($state),
        };
    }),
                  
            ])
            ->filters([
                SelectFilter::make('inv_no')->label('Invoice Number')
                    ->relationship('invoice', 'inv_no'),
            ])
            ->actions([
                 EditAction::make(),
                 DeleteAction::make(),
                  Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->url(fn ($record): string => route('receipt.report', ['record' => $record]), true),
           
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
            'index' => ListReceipts::route('/'),
            'create' => CreateReceipt::route('/create'),
            'view' => ViewReceipt::route('/{record}'),
            'edit' => EditReceipt::route('/{record}/edit'),
        ];
    }
}
