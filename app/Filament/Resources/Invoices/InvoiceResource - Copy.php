<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\Tables\InvoicesTable;
use App\Models\Invoice;
use App\Models\invoiceproduct;
use App\Models\Customer; 
use App\Models\Product;   
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Radio;  
use Filament\Forms\Components\Textarea;  
use Filament\Schemas\Components\Fieldset;
use app\Models\settings;
use Filament\Actions\Action;
use app\Models\Quotation;
use Closure;
class InvoiceResource_copy extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'invoice';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
{   
    $inv_num_job = Invoice::max('id') ?? 0;
    $inv_num_job++;
    $currentYear = date("Y");
    $invjob = 'ABC-' . $currentYear . '-' . str_pad($inv_num_job, 5, '0', STR_PAD_LEFT);
    $cust = Customer::pluck('name', 'id');
    $product = Product::pluck('prod_name', 'id');
    $taxPerc = Settings::value('tax_perc') ?? 0;
    $quote = Quotation::pluck('quote_no','id');

    
     return $schema
    ->components([
        TextInput::make('inv_no')->default($invjob)->label('Invoice Number')->readonly()->required(),
        Select::make('quote_no')->options($quote)->label('Quote Number'),
        Select::make('status')->label('Payment Status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'partial' => 'Partial',
                ])->default('pending')->required(),
        Select::make('cust_id')->options($cust)->label('Customer Name')->required(),
        Repeater::make('invoiceproducts')
        ->label('Invoice Items')
        ->relationship()
        ->columnSpanFull()
        ->reactive()
        
        ->schema([
            Select::make('product_id')->options($product)->label('Product')
            ->afterStateUpdated(function (callable $set, $state) {
                if ($state) {
                    $price = Product::find($state)?->prod_amount ?? 0;
                    $set('price', $price);
                }
            })
            ->columnSpan(['md' => 3])
            ->required(),

            TextInput::make('quantity')
                ->label('Qty/Weight')
                ->numeric()
                ->required()
                ->reactive()
                 ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $price = floatval($get('price') ?? 0);
                    $set('total_price', $price * floatval($state));
                  InvoiceResource::recalculateTotals($get, $set);
                })
                ->columnSpan(['md' => 2]),
            Select::make('unit')
                ->label('Unit')
                ->options([
                    'kg' => 'Kg',
                    'g' => 'Gram',
                    'ton' => 'Ton',
                    'lb' => 'Lb',
                    'nos' => 'Nos',
                ])
                ->required()
                ->columnSpan(['md' => 2]),

            TextInput::make('price')
                ->label('Price(per unit)')
                ->numeric()
                ->required()
                ->reactive()
                
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $type = $get('type');
                    $qty = floatval($get('quantity') ?? 0);
                    $set('total_price', floatval($state) * $qty);
                    //InvoiceResource::recalculateTotals($get, $set);
                })
                ->columnSpan(['md' => 2]),

            TextInput::make('total_price')
                ->label('Total Price')
                ->numeric()
                ->readonly()
                 ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    InvoiceResource::recalculateTotals($get, $set);
                })
                ->reactive()
                
                ->columnSpan(['md' => 2])
             
                ,
        ])
        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                     InvoiceResource::recalculateTotals($get, $set);
                                 })
        ->columns(12),
        Fieldset::make('')
        ->columnSpanFull()
        ->schema([
            Grid::make(2)
                ->schema([

                    Grid::make()
                        ->schema([
                            Textarea::make('terms')
                                ->label('Terms & Conditions')
                                ->rows(6)
                                ->columnSpan(4),
                        ]),

                    Grid::make()
                        ->schema([
                            TextInput::make('inv_subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                // ->disabled()
                                ->reactive()
                                
                                
                                ->columnSpan(4),

                            TextInput::make('inv_tax')
                                ->label('Tax '.$taxPerc.'%')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $taxPerc = Settings::value('tax_perc') ?? 0;
                                   $tax= $get('inv_subtotal')*($taxPerc/100);
                                   $set('inv_tax',$tax);
                                        }),

                            TextInput::make('inv_dis')
                                ->label('Discount')
                                ->numeric()
                                ->reactive()
                                ->debounce(250)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    InvoiceResource::recalculateTotals($get, $set);
                                        }),
                            TextInput::make('inv_total')
                                ->label('Total')
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(4),
                        ]),
                ])->columnSpanFull(),
        ]),
    ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inv_no')->label('Invoice Number')->searchable(),
                TextColumn::make('customer.name')->label('Customer Name'),
                TextColumn::make('inv_total')->label('Total')->searchable(),
                  
            ])
            ->filters([
                SelectFilter::make('inv_no'),
                SelectFilter::make('customer.name'),
            ])
            ->actions([
                 EditAction::make(),
                 DeleteAction::make(),
                 Action::make('pdf')
                    ->label('PDF')
                    ->color('success')
                    ->url(fn ($record): string => route('invoicepdf.report', ['record' => $record]), true),
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
  public static function recalculateTotals( callable $get, callable $set): void
{    
    // Get all repeater items
    $items = $get('invoiceproducts')?? []; 
    // Calculate subtotal (sum of total_price)
    $subtotal = array_reduce($items, fn($carry, $item) => $carry + (floatval($item['quantity'] ?? 0) * floatval($item['price'] ?? 0)), 0);
    $set('inv_subtotal', $subtotal);
    
    // Get tax percentage
    $taxPerc = Settings::value('tax_perc') ?? 0;
    $taxAmount = $subtotal * ($taxPerc / 100);
    $set('inv_tax', $taxAmount);
    
    // Get discount
    $discount = floatval($get('inv_dis') ?? 0);
    
    // Calculate final total
    $total = $subtotal + $taxAmount - $discount;
    $set('inv_total', $total);
}




}