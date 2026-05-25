<?php

declare(strict_types=1);

namespace App\Filament\Resources\Feedback\Pages;

use App\Filament\Resources\Feedback\FeedbackResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewFeedback extends ViewRecord
{
    protected static string $resource = FeedbackResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Информация')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Имя'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('subject')
                            ->label('Тема'),
                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'new' => 'Новое',
                                'processed' => 'Обработано',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'new' => 'info',
                                'processed' => 'success',
                                default => 'gray',
                            }),
                        TextEntry::make('ip_address')
                            ->label('IP'),
                        TextEntry::make('created_at')
                            ->label('Дата')
                            ->dateTime('d.m.Y H:i'),
                    ])
                    ->columns(2),

                Section::make('Сообщение')
                    ->schema([
                        TextEntry::make('message')
                            ->label(''),
                    ]),
            ]);
    }
}
