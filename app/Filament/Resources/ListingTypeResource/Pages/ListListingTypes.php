<?php

declare(strict_types=1);

namespace App\Filament\Resources\ListingTypeResource\Pages;

use App\Filament\Resources\ListingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListingTypes extends ListRecords
{
    protected static string $resource = ListingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
