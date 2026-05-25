<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                        TextEntry::make('reportable_type')
                            ->label('Тип объекта')
                            ->formatStateUsing(fn (string $state): string => class_basename($state)),
                        TextEntry::make('reportable_id')
                            ->label('ID объекта'),
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
