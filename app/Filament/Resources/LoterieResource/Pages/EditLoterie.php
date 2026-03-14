<?php

namespace App\Filament\Resources\LoterieResource\Pages;

use App\Filament\Resources\LoterieResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditLoterie extends EditRecord
{
    protected static string $resource = LoterieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.cancel.label'))
            ->url($this->getResource()::getUrl('index'))
            ->color('gray');
    }
}
