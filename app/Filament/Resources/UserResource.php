<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $navigationGroup = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('email')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('username')
                    ->label('username')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('verified')
                    ->label('verified')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('password')
                    ->label('password')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('profile_picture')
                    ->label('profile picture')
                    ->maxLength(100),
                Forms\Components\TextInput::make('ktp')
                    ->label('ktp')
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('username')->label('username')->searchable(),
                Tables\Columns\TextColumn::make('verified')->label('verified')->searchable(),
                Tables\Columns\TextColumn::make('profile_picture')->label('Profile Picture')->searchable(),
                Tables\Columns\TextColumn::make('ktp')->label('ktp')->searchable(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
