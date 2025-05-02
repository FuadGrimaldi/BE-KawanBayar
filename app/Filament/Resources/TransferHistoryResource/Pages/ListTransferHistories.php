<?php

namespace App\Filament\Resources\TransferHistoryResource\Pages;

use App\Filament\Resources\TransferHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransferHistories extends ListRecords
{
    protected static string $resource = TransferHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
