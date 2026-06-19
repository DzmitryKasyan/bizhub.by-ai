<?php

declare(strict_types=1);

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditListing extends EditRecord
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $listing = $this->getRecord();

        $data['contacts']['phone'] = $listing->contacts->firstWhere('type', 'phone')?->value;
        $data['contacts']['telegram'] = $listing->contacts->firstWhere('type', 'telegram')?->value;

        if ($listing->coordinate) {
            $data['coordinate']['latitude'] = $listing->coordinate->latitude;
            $data['coordinate']['longitude'] = $listing->coordinate->longitude;
            $data['coordinate']['address'] = $listing->coordinate->address;
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $contacts = $data['contacts'] ?? [];
        $coordinate = $data['coordinate'] ?? [];

        unset($data['contacts'], $data['coordinate']);

        $record->update($data);
        $record->saveContactsAndCoordinate([
            'contacts' => $contacts,
            'coordinate' => $coordinate,
        ]);

        return $record;
    }
}
