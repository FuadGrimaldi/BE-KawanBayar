<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferHistoryResource\Pages;
use App\Filament\Resources\TransferHistoryResource\RelationManagers;
use App\Models\TransferHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransferHistoryResource extends Resource
{
    protected static ?string $model = TransferHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transfer History';
    protected static ?string $navigationGroup = 'Histories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sender_id')
                ->label('Sender')
                ->relationship('sender', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('receiver_id')
                ->label('Receiver')
                ->relationship('receiver', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('transaction_code')
                ->label('Transaction Code')
                ->required()
                ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')->label('Sender')->searchable(),
                Tables\Columns\TextColumn::make('receiver.name')->label('Receiver')->searchable(),
                Tables\Columns\TextColumn::make('transaction_code')->label('Transaction Code'),
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
            'index' => Pages\ListTransferHistories::route('/'),
            'create' => Pages\CreateTransferHistory::route('/create'),
            'edit' => Pages\EditTransferHistory::route('/{record}/edit'),
        ];
    }
}
