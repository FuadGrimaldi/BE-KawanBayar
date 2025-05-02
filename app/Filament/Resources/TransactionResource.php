<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $navigationGroup = 'Histories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name') // Pastikan relasi 'user' ada di model BankAccount
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('transaction_type_id')
                    ->label('Transaction Type')
                    ->relationship('transactionType', 'name') // Pastikan relasi 'user' ada di model BankAccount
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('payment_method_id')
                    ->label('Payment Method')
                    ->relationship('paymentMethod', 'name') // Pastikan relasi 'user' ada di model BankAccount
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name') // Pastikan relasi 'user' ada di model BankAccount
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('transaction_code')
                    ->label('Transaction Code')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('description')
                    ->label('Desc')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('transactionType.name')->label('Transaction Type'),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Payment Method'),
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('amount')->label('Amount'),
                Tables\Columns\TextColumn::make('transaction_code')->label('Transaction Code'),
                Tables\Columns\TextColumn::make('description')->label('Desc'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
