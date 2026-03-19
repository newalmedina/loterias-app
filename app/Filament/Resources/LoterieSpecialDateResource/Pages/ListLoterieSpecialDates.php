<?php

namespace App\Filament\Resources\LoterieSpecialDateResource\Pages;

use App\Filament\Resources\LoterieSpecialDateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoterieSpecialDates extends ListRecords
{
    protected static string $resource = LoterieSpecialDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
