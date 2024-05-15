<?php

namespace App\Filament\Resources\RequisitionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequisitionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'requisitionItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('Item Description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('code')
                        ->helperText('Item model number, sku, etc.'),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1),

                Forms\Components\TextInput::make('unit_price')
                    ->numeric()
                    ->prefix('$'),


            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextInputColumn::make('description'),
                Tables\Columns\TextInputColumn::make('code'),
                Tables\Columns\TextInputColumn::make('quantity')
                    ->rules(['required','numeric','min:0']),
                Tables\Columns\TextInputColumn::make('unit_price')
                    ->rules(['required','numeric','decimal:2']),
                // Tables\Column\TextColumn::make('price')
                //     ->
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
