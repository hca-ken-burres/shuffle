<?php

namespace App\Filament\Resources;

use App\Enums\PaymentMethod;
use App\Enums\RequisitionCategory;
use App\Filament\Resources\RequisitionResource\Pages;
use App\Filament\Resources\RequisitionResource\RelationManagers;
use App\Filament\Resources\RequisitionResource\RelationManagers\RequisitionItemsRelationManager;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Year;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class RequisitionResource extends Resource
{
    protected static ?string $model = Requisition::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        return $user->can('edit requisitions')
            ? $query
            : $query->where('user_id', $user->id);

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::user()->id),
                        Forms\Components\Select::make('year_id')
                            ->label('School Year')
                            ->options(Year::all()->pluck('name','id'))
                            ->preload()
                            ->required()
                            ->helperText("The year for which the items should be acquired."),
                        Forms\Components\Select::make('category')
                            ->required()
                            ->preload()
                            ->enum(RequisitionCategory::class)
                            ->options(RequisitionCategory::class),
                        Forms\Components\TextInput::make('reason')
                            ->label('Reason for Requisition')
                            ->required()
                            ->lazy(),
                        Forms\Components\Select::make('vendor_id')
                            ->relationship('vendor', 'name')
                            ->createOptionForm(VendorResource::getForm())
                            ->preload()
                            ->searchable()
                            ->required()
                            ->live(),
                    ])->columns(2),

                Section::make('Items')->schema([

                    Repeater::make('items')
                        ->label('')
                        ->schema([
                            TextInput::make('description')
                                ->maxLength(160)
                                ->required()
                                ->helperText("The name or description of item.")
                                ->columnSpan(8),
                            TextInput::make('code')
                                ->label('Item Code')
                                ->maxLength(30)
                                ->helperText("Item code, sku, etc.")
                                ->columnSpan(2),
                            TextInput::make('url')
                                ->label('URL / Link')
                                ->url()
                                ->helperText('Paste entire link to item')
                                ->maxLength(1000)
                                ->prefixIcon('heroicon-o-link')
                                ->columnSpan(5),
                            TextInput::make('quantity')
                                ->integer()
                                ->required()
                                ->default(1)
                                ->lazy()
                                ->columnSpan(1),
                             TextInput::make('unit_price')
                                ->numeric()
                                ->inputMode('decimal')
                                ->rules(['required','numeric'])
                                ->prefix('$')
                                ->required()
                                ->helperText('The price of a single item')
                                ->lazy()
                               ->columnSpan(2),
                            Placeholder::make('Subtotal')
                                ->content(function($get) {
                                    $qty = $get('quantity');
                                    $unit_price = $get('unit_price');
                                    $cost = number_format($qty * $unit_price, 2);
                                    return view('custom.currency', ['value' => $cost]);
                                    // return new HtmlString('<div class="sm:leading-6 py-1.5 px-3 text-black dark:text-white rounded-lg ring-1 ring-gray-950/10 dark:ring-white/20" ><span class="">$' . $cost . '</span></div>');
                                })->columnSpan(2),
                        ])->columns(10)->defaultItems(1)->addActionLabel('Add Item'),
                        Placeholder::make('totalCost')
                            ->label('')
                            ->content(function($get) {
                                $cost = collect($get('items'))
                                    ->map(fn($i) => $i['quantity'] * $i['unit_price'])
                                    ->sum();
                                $cost = number_format($cost, 2);
                                return new HtmlString('<div class="flex justify-between border-t px-3 py-1.5 font-bold dark:text-white text-black text-lg border-gray-600 dark:border-gray-300 "><span class="block">Total</span><span class="block text-right">$' . $cost . '</span></div>');
                            })

                ]),
                Section::make('Payment Details')
                    ->collapsible()
                    ->schema([
                    Forms\Components\Select::make('payment_method')
                        ->enum(PaymentMethod::class)
                        ->options(PaymentMethod::class)
                        ->hiddenOn('create'),
                    Forms\Components\TextInput::make('payment_note'),
                    Forms\Components\TextInput::make('account_num')
                        ->label('General Ledger Account #'),
                ])->hiddenOn(['create'])
                ->hidden(fn() => !Auth::user()->can('edit requisitions')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creator / Description')
                    ->numeric()
                    ->sortable()
                    ->description(fn($record) => Str::of($record->reason)->limit(30)),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->color(fn($state) => $state->getColor()),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('reason')
                //     ->searchable()
                //     ->hiddenOn(['index']),
                Tables\Columns\TextColumn::make('total_cost')
                    ->label('Total')
                    ->getStateUsing(fn($record) => $record->totalCost())
                    ->prefix('$'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn($state) => $state->getColor()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('owner')
                    ->relationship('user', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('vendor')
                    ->relationship('vendor', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RequisitionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequisitions::route('/'),
            'create' => Pages\CreateRequisition::route('/create'),
            // 'edit' => Pages\EditRequisition::route('/{record}/edit'),
        ];
    }
}
