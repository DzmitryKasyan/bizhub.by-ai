<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Filament\Resources\ListingResource\Pages\EditListing;
use App\Filament\Resources\ListingResource\Pages\ListListings;
use App\Filament\Resources\ListingResource\Pages\ViewListing;
use App\Models\Listing;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Объявления';

    protected static ?string $modelLabel = 'Объявление';

    protected static ?string $pluralModelLabel = 'Объявления';

    protected static string|\UnitEnum|null $navigationGroup = 'Модерация';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Тип')
                            ->options(
                                collect(ListingType::cases())
                                    ->mapWithKeys(fn (ListingType $type): array => [
                                        $type->value => $type->label(),
                                    ])
                                    ->all()
                            )
                            ->required(),

                        Select::make('status')
                            ->label('Статус')
                            ->options(
                                collect(ListingStatus::cases())
                                    ->mapWithKeys(fn (ListingStatus $status): array => [
                                        $status->value => $status->label(),
                                    ])
                                    ->all()
                            )
                            ->required(),

                        Select::make('category_id')
                            ->label('Категория')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('location_id')
                            ->label('Регион')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                Section::make('Цена')
                    ->schema([
                        TextInput::make('price')
                            ->label('Цена')
                            ->numeric()
                            ->default(0),

                        TextInput::make('price_max')
                            ->label('Цена до')
                            ->numeric(),

                        Select::make('currency')
                            ->label('Валюта')
                            ->options(
                                collect(Currency::cases())
                                    ->mapWithKeys(fn (Currency $currency): array => [
                                        $currency->value => $currency->label(),
                                    ])
                                    ->all()
                            )
                            ->required(),

                        Toggle::make('price_negotiable')
                            ->label('Цена договорная'),

                        Toggle::make('price_on_request')
                            ->label('Цена по запросу'),

                        Toggle::make('is_representative')
                            ->label('Представитель собственника'),

                        TextInput::make('representative_note')
                            ->label('Примечание представителя')
                            ->maxLength(255),
                    ])
                    ->columns(4),

                Section::make('Описание')
                    ->schema([
                        Textarea::make('description')
                            ->label('Описание')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Section::make('Контакты')
                    ->schema([
                        TextInput::make('contacts.phone')
                            ->label('Телефон'),

                        TextInput::make('contacts.telegram')
                            ->label('Telegram'),
                    ])
                    ->columns(2),

                Section::make('Координаты')
                    ->schema([
                        TextInput::make('coordinate.latitude')
                            ->label('Широта')
                            ->numeric(),

                        TextInput::make('coordinate.longitude')
                            ->label('Долгота')
                            ->numeric(),

                        TextInput::make('coordinate.address')
                            ->label('Адрес на карте')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                ImageColumn::make('mainImage')
                    ->label('Фото')
                    ->square()
                    ->size(40)
                    ->disk('public')
                    ->defaultImageUrl(fn () => null),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(fn (Listing $record): string => $record->title),

                TextColumn::make('user.name')
                    ->label('Владелец')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn (ListingType $state): string => $state->label())
                    ->icon(fn (ListingType $state): string => $state->icon()),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (ListingStatus $state): string => $state->label())
                    ->color(fn (ListingStatus $state): string => $state->color()),

                TextColumn::make('price')
                    ->label('Цена')
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ' ')
                    ->suffix(fn (Listing $record): string => $record->currency ? ' ' . $record->currency->symbol() : ''),

                IconColumn::make('price_on_request')
                    ->label('По запросу')
                    ->boolean(),

                IconColumn::make('is_representative')
                    ->label('Представитель')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(
                        collect(ListingStatus::cases())
                            ->mapWithKeys(fn (ListingStatus $status): array => [
                                $status->value => $status->label(),
                            ])
                            ->all()
                    ),

                SelectFilter::make('type')
                    ->label('Тип')
                    ->options(
                        collect(ListingType::cases())
                            ->mapWithKeys(fn (ListingType $type): array => [
                                $type->value => $type->label(),
                            ])
                            ->all()
                    ),

                TernaryFilter::make('is_representative')
                    ->label('Представитель собственника'),

                TernaryFilter::make('price_on_request')
                    ->label('Цена по запросу'),

                Filter::make('created_at')
                    ->label('Дата создания')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('С'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('По'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $q, string $date): Builder => $q->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = 'С: ' . $data['created_from'];
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = 'По: ' . $data['created_until'];
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('approve')
                    ->label('Одобрить')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Одобрить объявление')
                    ->modalDescription('Объявление будет переведено в статус "Активно".')
                    ->action(function (Listing $record): void {
                        $record->update(['status' => ListingStatus::Active]);
                    })
                    ->visible(fn (Listing $record): bool => $record->status === ListingStatus::Pending),

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
                    ->action(function (Listing $record, array $data): void {
                        $record->update([
                            'status'           => ListingStatus::Rejected,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                    })
                    ->visible(fn (Listing $record): bool => in_array(
                        $record->status,
                        [ListingStatus::Pending, ListingStatus::Active],
                        strict: true,
                    )),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Одобрить выбранные')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Массовое одобрение')
                        ->modalDescription('Будут одобрены только объявления со статусом "На модерации".')
                        ->action(function (Collection $records): void {
                            $records
                                ->filter(fn (Listing $r): bool => $r->status === ListingStatus::Pending)
                                ->each(fn (Listing $r): bool => $r->update(['status' => ListingStatus::Active]));
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('mark_representative')
                        ->label('Отметить как представителя')
                        ->icon('heroicon-o-user')
                        ->color('warning')
                        ->action(function (Collection $records): void {
                            $records->each(fn (Listing $r): bool => $r->update([
                                'is_representative' => true,
                                'representative_note' => $r->representative_note ?: config('bizhub.representative_default_note'),
                            ]));
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('unmark_representative')
                        ->label('Убрать метку представителя')
                        ->icon('heroicon-o-user')
                        ->color('gray')
                        ->action(function (Collection $records): void {
                            $records->each(fn (Listing $r): bool => $r->update(['is_representative' => false]));
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
                Section::make('Основная информация')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID'),

                        TextEntry::make('title')
                            ->label('Заголовок'),

                        TextEntry::make('slug')
                            ->label('Slug'),

                        TextEntry::make('type')
                            ->label('Тип')
                            ->badge()
                            ->formatStateUsing(fn (ListingType $state): string => $state->label()),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->formatStateUsing(fn (ListingStatus $state): string => $state->label())
                            ->color(fn (ListingStatus $state): string => $state->color()),

                        TextEntry::make('user.name')
                            ->label('Владелец'),

                        TextEntry::make('category.name')
                            ->label('Категория'),

                        TextEntry::make('location.name')
                            ->label('Регион'),
                    ])
                    ->columns(2),

                Section::make('Цена')
                    ->schema([
                        TextEntry::make('price')
                            ->label('Цена')
                            ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ' '),

                        TextEntry::make('currency')
                            ->label('Валюта')
                            ->formatStateUsing(fn (mixed $state): string => $state?->symbol() ?? '—'),

                        TextEntry::make('price_negotiable')
                            ->label('Цена договорная')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Да' : 'Нет'),
                    ])
                    ->columns(3),

                Section::make('Изображения')
                    ->schema([
                        \Filament\Infolists\Components\ImageEntry::make('mainImage')
                            ->label('Главное изображение')
                            ->disk('public')
                            ->size(200)
                            ->defaultImageUrl(null),
                    ]),

                Section::make('Описание')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Описание')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Модерация')
                    ->schema([
                        TextEntry::make('rejection_reason')
                            ->label('Причина отклонения')
                            ->placeholder('Не указана')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Listing $record): bool => $record->status === ListingStatus::Rejected),

                Section::make('Статистика')
                    ->schema([
                        TextEntry::make('views_count')
                            ->label('Просмотры'),

                        TextEntry::make('favorites_count')
                            ->label('В избранном'),

                        TextEntry::make('responses_count')
                            ->label('Откликов'),

                        TextEntry::make('created_at')
                            ->label('Создано')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Обновлено')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('expires_at')
                            ->label('Истекает')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('Без срока'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListings::route('/'),
            'view'  => ViewListing::route('/{record}'),
            'edit'  => EditListing::route('/{record}/edit'),
        ];
    }
}