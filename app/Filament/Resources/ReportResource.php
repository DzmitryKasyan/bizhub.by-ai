<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages\ListReports;
use App\Models\Report;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = 'Жалобы';

    protected static ?string $modelLabel = 'Жалоба';

    protected static ?string $pluralModelLabel = 'Жалобы';

    protected static string|\UnitEnum|null $navigationGroup = 'Модерация';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('reporter.name')
                    ->label('Жалобщик')
                    ->searchable(),

                TextColumn::make('reportable_type')
                    ->label('Тип объекта')
                    ->formatStateUsing(
                        fn (string $state): string => class_basename($state)
                    ),

                TextColumn::make('reportable_id')
                    ->label('ID объекта'),

                TextColumn::make('reason')
                    ->label('Причина')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'   => 'warning',
                        'reviewed'  => 'info',
                        'resolved'  => 'success',
                        'dismissed' => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'   => 'Ожидает',
                        'reviewed'  => 'Проверяется',
                        'resolved'  => 'Решено',
                        'dismissed' => 'Отклонено',
                        default     => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'pending'   => 'Ожидает',
                        'reviewed'  => 'Проверяется',
                        'resolved'  => 'Решено',
                        'dismissed' => 'Отклонено',
                    ]),
            ])
            ->actions([
                Action::make('resolve')
                    ->label('Решено')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Отметить как решённое')
                    ->modalDescription('Жалоба будет отмечена как решённая.')
                    ->action(function (Report $record): void {
                        $record->resolve(auth()->user());
                    })
                    ->visible(
                        fn (Report $record): bool => in_array($record->status, ['pending', 'reviewed'], strict: true)
                    ),

                Action::make('dismiss')
                    ->label('Отклонить')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Отклонить жалобу')
                    ->modalDescription('Жалоба будет отклонена без принятия мер.')
                    ->action(function (Report $record): void {
                        $record->dismiss(auth()->user());
                    })
                    ->visible(
                        fn (Report $record): bool => in_array($record->status, ['pending', 'reviewed'], strict: true)
                    ),

                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->isAdmin() ?? false),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReports::route('/'),
        ];
    }
}
