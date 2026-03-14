<?php

namespace App\Filament\Resources\CenterLoterieResource\Pages;

use App\Filament\Resources\CenterLoterieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCenterLoteries extends ListRecords
{
    protected static string $resource = CenterLoterieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
