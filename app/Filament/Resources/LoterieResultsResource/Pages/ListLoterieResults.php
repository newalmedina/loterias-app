<?php

namespace App\Filament\Resources\LoterieResultsResource\Pages;

use App\Filament\Resources\LoterieResultsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoterieResults extends ListRecords
{
    protected static string $resource = LoterieResultsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn() => auth()->user()?->super_admin),
        ];
    }
}
