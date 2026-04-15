<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@bizhub.by'],
            [
                'name' => 'Администратор BizHub',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@BizHub2024!')),
                'role' => UserRole::Admin,
                'is_verified' => true,
                'is_premium' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin created: {$admin->email}");

        // Moderator
        $moderator = User::updateOrCreate(
            ['email' => 'moderator@bizhub.by'],
            [
                'name' => 'Модератор BizHub',
                'password' => Hash::make(env('MODERATOR_PASSWORD', 'Mod@BizHub2024!')),
                'role' => UserRole::Moderator,
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Moderator created: {$moderator->email}");

        // Demo Entrepreneur
        $entrepreneur = User::updateOrCreate(
            ['email' => 'demo_seller@bizhub.by'],
            [
                'name' => 'Иван Петров',
                'password' => Hash::make('Demo@Seller2024!'),
                'role' => UserRole::Entrepreneur,
                'company_name' => 'ООО «ТестКомпани»',
                'bio' => 'Опытный предприниматель с 10-летним стажем в розничной торговле.',
                'is_verified' => true,
                'email_verified_at' => now(),
                'phone' => '+375291234567',
                'phone_verified_at' => now(),
            ]
        );

        $this->command->info("Demo entrepreneur created: {$entrepreneur->email}");

        // Demo Investor
        $investor = User::updateOrCreate(
            ['email' => 'demo_investor@bizhub.by'],
            [
                'name' => 'Алексей Сидоров',
                'password' => Hash::make('Demo@Investor2024!'),
                'role' => UserRole::Investor,
                'bio' => 'Частный инвестор. Рассматриваю проекты в сфере IT, торговли и производства.',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Demo investor created: {$investor->email}");
    }
}
