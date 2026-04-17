<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify')
                ->label('Верифицировать')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Верифицировать пользователя')
                ->modalDescription('Пользователь получит статус верифицированного.')
                ->action(function (): void {
                    /** @var User $record */
                    $record = $this->getRecord();
                    $record->update(['is_verified' => true]);
                    $this->refreshFormData(['is_verified']);
                })
                ->visible(fn (): bool => ! $this->getRecord()->is_verified),

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
                        ->default(fn (): string => $this->getRecord()->role->value)
                        ->required(),
                ])
                ->modalHeading('Изменить роль пользователя')
                ->action(function (array $data): void {
                    /** @var User $record */
                    $record = $this->getRecord();
                    $record->update(['role' => $data['role']]);
                    $this->refreshFormData(['role']);
                })
                ->visible(fn (): bool => auth()->user()?->isAdmin() ?? false),

            Action::make('ban')
                ->label('Заблокировать')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Заблокировать пользователя')
                ->modalDescription('Пользователь будет удалён (soft delete) и не сможет войти в систему.')
                ->action(function (): void {
                    /** @var User $record */
                    $record = $this->getRecord();
                    $record->delete();
                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->visible(fn (): bool => $this->getRecord()->role !== UserRole::Admin),
        ];
    }
}
