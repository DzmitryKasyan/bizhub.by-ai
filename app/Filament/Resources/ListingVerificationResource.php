<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\VerificationStatus;
use App\Enums\VerificationType;
use App\Filament\Resources\ListingVerificationResource\Pages\EditListingVerification;
use App\Filament\Resources\ListingVerificationResource\Pages\ListListingVerifications;
use App\Models\ListingVerification;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ListingVerificationResource extends Resource
{
    protected static ?string $model = ListingVerification::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Верификации лотов';

    protected static ?string $modelLabel = 'Верификация';

    protected static ?string $pluralModelLabel = 'Верификации лотов';

    protected static string|\UnitEnum|null $navigationGroup = 'Модерация';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Информация')
                    ->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->options(
                                collect(VerificationStatus::cases())
                                    ->mapWithKeys(fn (VerificationStatus $status): array => [
                                        $status->value => $status->label(),
                                    ])
                                    ->all()
                            )
                            ->required(),

                        Textarea::make('notes')
                            ->label('Примечания')
                            ->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('listing.title')
                    ->label('Объявление')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn (VerificationType $state): string => $state->shortLabel())
                    ->color(fn (VerificationType $state): string => $state->color()),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (VerificationStatus $state): string => $state->label())
                    ->color(fn (VerificationStatus $state): string => $state->color()),

                TextColumn::make('reviewer.name')
                    ->label('Проверил')
                    ->placeholder('—'),

                TextColumn::make('reviewed_at')
                    ->label('Проверено')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Тип')
                    ->options(
                        collect(VerificationType::cases())
                            ->mapWithKeys(fn (VerificationType $type): array => [
                                $type->value => $type->shortLabel(),
                            ])
                            ->all()
                    ),

                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(
                        collect(VerificationStatus::cases())
                            ->mapWithKeys(fn (VerificationStatus $status): array => [
                                $status->value => $status->label(),
                            ])
                            ->all()
                    ),
            ])
            ->actions([
                EditAction::make(),

                Action::make('approve')
                    ->label('Подтвердить')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Подтвердить верификацию')
                    ->action(function (ListingVerification $record): void {
                        $record->update([
                            'status' => VerificationStatus::Approved,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);
                    })
                    ->visible(fn (ListingVerification $record): bool => $record->status !== VerificationStatus::Approved),

                Action::make('reject')
                    ->label('Отклонить')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Отклонить верификацию')
                    ->action(function (ListingVerification $record): void {
                        $record->update([
                            'status' => VerificationStatus::Rejected,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);
                    })
                    ->visible(fn (ListingVerification $record): bool => $record->status !== VerificationStatus::Rejected),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('approve')
                        ->label('Подтвердить выбранные')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(fn (ListingVerification $r): bool => $r->update([
                                'status' => VerificationStatus::Approved,
                                'reviewed_by' => auth()->id(),
                                'reviewed_at' => now(),
                            ]));
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('reject')
                        ->label('Отклонить выбранные')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(fn (ListingVerification $r): bool => $r->update([
                                'status' => VerificationStatus::Rejected,
                                'reviewed_by' => auth()->id(),
                                'reviewed_at' => now(),
                            ]));
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Верификация')
                    ->schema([
                        TextEntry::make('listing.title')
                            ->label('Объявление'),

                        TextEntry::make('user.name')
                            ->label('Пользователь'),

                        TextEntry::make('type')
                            ->label('Тип')
                            ->badge()
                            ->formatStateUsing(fn (VerificationType $state): string => $state->label()),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->formatStateUsing(fn (VerificationStatus $state): string => $state->label())
                            ->color(fn (VerificationStatus $state): string => $state->color()),

                        TextEntry::make('notes')
                            ->label('Примечания')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListingVerifications::route('/'),
            'edit'  => EditListingVerification::route('/{record}/edit'),
        ];
    }
}
