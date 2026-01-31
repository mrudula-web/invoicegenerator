<?php

namespace App\Filament\Resources\Quotations;

use App\Filament\Resources\Quotations\Pages\CreateQuotation;
use App\Filament\Resources\Quotations\Pages\EditQuotation;
use App\Filament\Resources\Quotations\Pages\ListQuotations;
use App\Filament\Resources\Quotations\Schemas\QuotationForm;
use App\Filament\Resources\Quotations\Tables\QuotationsTable;
use App\Models\Quotation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
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
use App\Models\Customer; 
use App\Models\Product;
use App\Models\quoteproducts;


class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $recordTitleAttribute = 'quotation';
    protected static ?int $navigationSort = 3;
 public static function getGloballySearchableAttributes(): array
{
    return ['quote_no'];
}
    public static function form(Schema $schema): Schema
    {
        $quote_num_job = Quotation::max('id') ?? 0;
    $quote_num_job++;
    $currentYear = date("Y");
    $quotejob = 'ABC-' . $currentYear . '-' . str_pad($quote_num_job, 5, '0', STR_PAD_LEFT);
    $cust = Customer::pluck('name', 'id');
    $product = Product::pluck('prod_name', 'id');
    $taxPerc = Settings::value('tax_perc') ?? 0;
    
     return $schema
    ->components([
        TextInput::make('quote_no')->default($quotejob)->label('Quote Number')->readonly(),
        Select::make('quotecust_id')->options($cust)->label('Customer Name')->required(),
        Repeater::make('quoteproducts')
        ->label('Quote Items')
        ->relationship()
        ->columnSpanFull()
        ->reactive()
        
        ->schema([
            Select::make('quoteprod_id')->options($product)->label('Product')
            ->afterStateUpdated(function (callable $set, $state) {
                if ($state) {
                    $price = Product::find($state)?->prod_amount ?? 0;
                    $set('quote_price', $price);
                }
            })
            ->columnSpan(['md' => 3])
            ->required(),

            TextInput::make('quote_quantity')
                ->label('Qty/Weight')
                ->numeric()
                ->required()
                ->reactive()
                 ->debounce(500)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $price = floatval($get('quote_price') ?? 0);
                    $set('quotetotal_price', $price * floatval($state));
                  QuotationResource::recalculateTotals($get, $set);
                })
                ->columnSpan(['md' => 2]),
            Select::make('quote_unit')
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

            TextInput::make('quote_price')
                ->label('Price(per unit)')
                ->numeric()
                ->required()
                ->reactive()
                
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $type = $get('quote_type');
                    $qty = floatval($get('quote_quantity') ?? 0);
                    $set('quotetotal_price', floatval($state) * $qty);
                    //InvoiceResource::recalculateTotals($get, $set);
                })
                ->columnSpan(['md' => 2]),

            TextInput::make('quotetotal_price')
                ->label('Total Price')
                ->numeric()
                ->readonly()
                 ->afterStateUpdated(function ($state, callable $set, callable $get) {
                   QuotationResource::recalculateTotals($get, $set);
                })
                ->reactive()
                
                ->columnSpan(['md' => 2])
             
                ,
        ])
        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    QuotationResource::recalculateTotals($get, $set);
                                 })
        ->columns(12),
        Fieldset::make('')
        ->columnSpanFull()
        ->schema([
            Grid::make(2)
                ->schema([

                    Grid::make()
                        ->schema([
                            Textarea::make('quote_terms')
                                ->label('Terms & Conditions')
                                ->rows(6)
                                ->columnSpan(4),
                        ]),

                    Grid::make()
                        ->schema([
                            TextInput::make('quoteinv_subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                // ->disabled()
                                ->reactive()
                                
                                
                                ->columnSpan(4),

                            TextInput::make('quoteinv_tax')
                                ->label('Tax '.$taxPerc.'%')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $taxPerc = Settings::value('tax_perc') ?? 0;
                                   $tax= $get('quoteinv_subtotal')*($taxPerc/100);
                                   $set('quoteinv_tax',$tax);
                                        }),

                            TextInput::make('quoteinv_dis')
                                ->label('Discount')
                                ->numeric()
                                ->reactive()
                                ->debounce(250)
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                   QuotationResource::recalculateTotals($get, $set);
                                        }),
                            TextInput::make('quoteinv_total')
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
                TextColumn::make('quote_no')->label('Quotation Number')->searchable(),
                TextColumn::make('customer.name')->label('Customer Name'),
                TextColumn::make('quoteinv_total')->label('Total')->searchable(),
                  
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
                    ->url(fn ($record): string => route('quotation.report', ['record' => $record]), true),
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
            'index' => ListQuotations::route('/'),
            'create' => CreateQuotation::route('/create'),
            'edit' => EditQuotation::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
     public static function recalculateTotals( callable $get, callable $set): void
{    
    // Get all repeater items
    $items = $get('quoteproducts')?? []; 
    // Calculate subtotal (sum of total_price)
    $subtotal = array_reduce($items, fn($carry, $item) => $carry + (floatval($item['quote_quantity'] ?? 0) * floatval($item['quote_price'] ?? 0)), 0);
    $set('quoteinv_subtotal', $subtotal);
    
    // Get tax percentage
    $taxPerc = Settings::value('tax_perc') ?? 0;
    $taxAmount = $subtotal * ($taxPerc / 100);
    $set('quoteinv_tax', $taxAmount);
    
    // Get discount
    $discount = floatval($get('quoteinv_dis') ?? 0);
    
    // Calculate final total
    $total = $subtotal + $taxAmount - $discount;
    $set('quoteinv_total', $total);
}
}
