<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Пользователи';

    protected static ?string $modelLabel = 'Пользователь';

    protected static ?string $pluralModelLabel = 'Пользователи';

    protected static string|\UnitEnum|null $navigationGroup = 'Управление';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Телефон'),

                TextColumn::make('role')
                    ->label('Роль')
                    ->badge()
                    ->formatStateUsing(fn (UserRole $state): string => $state->label())
                    ->color(fn (UserRole $state): string => match ($state) {
                        UserRole::User         => 'gray',
                        UserRole::Entrepreneur => 'info',
                        UserRole::Investor     => 'success',
                        UserRole::Broker       => 'warning',
                        UserRole::Moderator    => 'primary',
                        UserRole::Admin        => 'danger',
                    }),

                IconColumn::make('is_verified')
                    ->label('Верифицирован')
                    ->boolean(),

                IconColumn::make('is_premium')
                    ->label('Премиум')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Зарегистрирован')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('role')
                    ->label('Роль')
                    ->options(
                        collect(UserRole::cases())
                            ->mapWithKeys(fn (UserRole $role): array => [
                                $role->value => $role->label(),
                            ])
                            ->all()
                    ),

                TernaryFilter::make('is_verified')
                    ->label('Верификация')
                    ->trueLabel('Верифицированные')
                    ->falseLabel('Не верифицированные'),

                TernaryFilter::make('is_premium')
                    ->label('Премиум')
                    ->trueLabel('Премиум')
                    ->falseLabel('Без премиума'),
            ])
            ->actions([
                ViewAction::make(),

                Action::make('verify')
                    ->label('Верифицировать')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Верифицировать пользователя')
                    ->modalDescription('Пользователь получит статус верифицированного.')
                    ->action(function (User $record): void {
                        $record->update(['is_verified' => true]);
                    })
                    ->visible(fn (User $record): bool => ! $record->is_verified),

                Action::make('changeRole')
                    ->label('Сменить роль')
                    ->icon('heroicon-o-user-circle')
                    ->color('warning')
                    ->form([
                        Select::make('role')
                            ->label('Роль')
                            ->options(
                                collect(UserRole::cases())
                                    ->mapWithKeys(fn (UserRole $role): array => [
                                        $role->value => $role->label(),
                                    ])
                                    ->all()
                            )
                            ->required(),
                    ])
                    ->modalHeading('Изменить роль пользователя')
                    ->action(function (User $record, array $data): void {
                        $record->update(['role' => $data['role']]);
                    })
                    ->visible(fn (): bool => auth()->user()?->isAdmin() ?? false),

                Action::make('ban')
                    ->label('Заблокировать')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Заблокировать пользователя')
                    ->modalDescription('Пользователь будет удалён (soft delete) и не сможет войти в систему.')
                    ->action(function (User $record): void {
                        $record->delete();
                    })
                    ->visible(fn (User $record): bool => $record->role !== UserRole::Admin),
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

                        TextEntry::make('name')
                            ->label('Имя'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('phone')
                            ->label('Телефон')
                            ->placeholder('Не указан'),

                        TextEntry::make('role')
                            ->label('Роль')
                            ->badge()
                            ->formatStateUsing(fn (UserRole $state): string => $state->label())
                            ->color(fn (UserRole $state): string => match ($state) {
                                UserRole::User         => 'gray',
                                UserRole::Entrepreneur => 'info',
                                UserRole::Investor     => 'success',
                                UserRole::Broker       => 'warning',
                                UserRole::Moderator    => 'primary',
                                UserRole::Admin        => 'danger',
                            }),

                        TextEntry::make('rating')
                            ->label('Рейтинг')
                            ->placeholder('Нет оценок'),

                        TextEntry::make('reviews_count')
                            ->label('Количество отзывов'),
                    ])
                    ->columns(2),

                Section::make('Статусы')
                    ->schema([
                        IconEntry::make('is_verified')
                            ->label('Верифицирован')
                            ->boolean(),

                        IconEntry::make('is_premium')
                            ->label('Премиум')
                            ->boolean(),
                    ])
                    ->columns(2),

                Section::make('Даты')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Зарегистрирован')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Обновлён')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('deleted_at')
                            ->label('Заблокирован')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('Не заблокирован'),
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
            'index' => ListUsers::route('/'),
            'view'  => ViewUser::route('/{record}'),
        ];
    }
}
