<?php

namespace App\Filament\Resources\OperatorCardResource\Pages;

use App\Filament\Resources\OperatorCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperatorCard extends EditRecord
{
    protected static string $resource = OperatorCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
