<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ListingResource;
use App\Filament\Resources\ReportResource;
use App\Models\Listing;
use App\Models\Report;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Информация о жалобе')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID'),
                        TextEntry::make('reporter.name')
                            ->label('Жалобщик'),
                        TextEntry::make('reportable')
                            ->label('Объект')
                            ->formatStateUsing(function (Report $record, $state): string {
                                $object = $state instanceof Model ? $state : $record->reportable;

                                if (! $object) {
                                    return 'Удалён (#' . $record->reportable_id . ')';
                                }

                                $type = $object instanceof Listing
                                    ? 'Объявление'
                                    : class_basename($object);

                                $title = $object instanceof Listing
                                    ? $object->title
                                    : ('#' . $object->getKey());

                                return $type . ': ' . $title;
                            })
                            ->url(function (Report $record): ?string {
                                if ($record->reportable instanceof Listing) {
                                    return ListingResource::getUrl('view', ['record' => $record->reportable]);
                                }

                                return null;
                            })
                            ->openUrlInNewTab(),
                        TextEntry::make('reason')
                            ->label('Причина')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'spam' => 'Спам',
                                'fraud' => 'Мошенничество',
                                'incorrect_info' => 'Неверная информация',
                                'other' => 'Другое',
                                default => $state,
                            }),
                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning', 'reviewed' => 'info',
                                'resolved' => 'success', 'dismissed' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Ожидает', 'reviewed' => 'Проверяется',
                                'resolved' => 'Решено', 'dismissed' => 'Отклонено',
                                default => $state,
                            }),
                        TextEntry::make('created_at')
                            ->label('Создано')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),

                Section::make('Описание')
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->formatStateUsing(fn (?string $state): string => $state ?: '(не указано)'),
                    ]),
            ]);
    }
}
