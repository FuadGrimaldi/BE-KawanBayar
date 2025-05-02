<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionTypeResource\Pages;
use App\Filament\Resources\TransactionTypeResource\RelationManagers;
use App\Models\TransactionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionTypeResource extends Resource
{
    protected static ?string $model = TransactionType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transactions Type';
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([            
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('action')
                    ->label('action')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('thumbnail')
                    ->label('Thumbnail')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\TextColumn::make('code')->label('Code'),
                Tables\Columns\TextColumn::make('action')->label('Action'),
                Tables\Columns\TextColumn::make('thumbnail')->label('Thumbnail'),
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
            'index' => Pages\ListTransactionTypes::route('/'),
            'create' => Pages\CreateTransactionType::route('/create'),
            'edit' => Pages\EditTransactionType::route('/{record}/edit'),
        ];
    }
}
