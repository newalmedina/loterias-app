<?php

namespace App\Filament\Resources\LoterieResultsResource\Pages;

use App\Filament\Resources\LoterieResultsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoterieResults extends EditRecord
{
    protected static string $resource = LoterieResultsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(fn() => auth()->user()?->super_admin),
        ];
    }
}
