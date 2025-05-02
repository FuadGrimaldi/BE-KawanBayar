<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataPlanResource\Pages;
use App\Filament\Resources\DataPlanResource\RelationManagers;
use App\Models\DataPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataPlanResource extends Resource
{
    protected static ?string $model = DataPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Plan Card';
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('operator_card_id')
                    ->label('OperatorCard')
                    ->relationship('operatorCard', 'name') // Pastikan relasi 'user' ada di model BankAccount
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('balance')
                    ->label('Balance')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('price')
                    ->label('price')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('operatorCard.name')->label('Operator Card')->searchable(),
                    Tables\Columns\TextColumn::make('name')->label('Name'),
                    Tables\Columns\TextColumn::make('price')->label('Price'),
                    Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataPlans::route('/'),
            'create' => Pages\CreateDataPlan::route('/create'),
            'edit' => Pages\EditDataPlan::route('/{record}/edit'),
        ];
    }
}
