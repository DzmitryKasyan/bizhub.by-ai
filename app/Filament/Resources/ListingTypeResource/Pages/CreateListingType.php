<?php

declare(strict_types=1);

namespace App\Filament\Resources\ListingTypeResource\Pages;

use App\Filament\Resources\ListingTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateListingType extends CreateRecord
{
    protected static string $resource = ListingTypeResource::class;
}
