<?php

namespace App\Filament\Resources\OperatorCardResource\Pages;

use App\Filament\Resources\OperatorCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOperatorCards extends ListRecords
{
    protected static string $resource = OperatorCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
