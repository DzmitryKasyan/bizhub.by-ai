<?php

declare(strict_types=1);

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Enums\ListingStatus;
use App\Models\Listing;
use Filament\Forms\Components\Textarea;

class ViewListing extends ViewRecord
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Одобрить')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Одобрить объявление')
                ->modalDescription('Объявление будет переведено в статус "Активно".')
                ->action(function (): void {
                    /** @var Listing $record */
                    $record = $this->getRecord();
                    $record->update(['status' => ListingStatus::Active]);
                    $this->refreshFormData(['status']);
                })
                ->visible(fn (): bool => $this->getRecord()->status === ListingStatus::Pending),

            Action::make('reject')
                ->label('Отклонить')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Причина отклонения')
                        ->placeholder('Укажите причину отклонения объявления...')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Отклонить объявление')
                ->action(function (array $data): void {
                    /** @var Listing $record */
                    $record = $this->getRecord();
                    $record->update([
                        'status'           => ListingStatus::Rejected,
                        'rejection_reason' => $data['rejection_reason'],
                    ]);
                    $this->refreshFormData(['status', 'rejection_reason']);
                })
                ->visible(fn (): bool => in_array(
                    $this->getRecord()->status,
                    [ListingStatus::Pending, ListingStatus::Active],
                    strict: true,
                )),
        ];
    }
}
