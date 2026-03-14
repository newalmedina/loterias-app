<?php

namespace App\Filament\Resources\LoterieResource\Pages;

use App\Filament\Resources\LoterieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoteries extends ListRecords
{
    protected static string $resource = LoterieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
