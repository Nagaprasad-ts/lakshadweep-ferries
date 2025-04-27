<?php

namespace App\Filament\Resources\FerryCategoryResource\Pages;

use App\Filament\Resources\FerryCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFerryCategory extends EditRecord
{
    protected static string $resource = FerryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
