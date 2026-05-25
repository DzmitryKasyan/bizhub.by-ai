<?php

declare(strict_types=1);

namespace App\Filament\Resources\ListingTypeResource\Pages;

use App\Filament\Resources\ListingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListingType extends EditRecord
{
    protected static string $resource = ListingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
