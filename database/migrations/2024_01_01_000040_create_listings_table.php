<?php

use App\Enums\Currency;
use App\Enums\ListingStatus;
use App\Enums\ListingType;
use App\Enums\OwnershipType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default(ListingType::SellBusiness->value);
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');

            // Pricing
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            $table->string('currency', 3)->default(Currency::BYN->value);
            $table->boolean('price_negotiable')->default(false);

            // Location
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Business financials
            $table->decimal('monthly_revenue', 15, 2)->nullable();
            $table->decimal('monthly_profit', 15, 2)->nullable();
            $table->unsignedSmallInteger('payback_months')->nullable();
            $table->decimal('investment_amount', 15, 2)->nullable();
            $table->unsignedSmallInteger('year_founded')->nullable();
            $table->unsignedInteger('employees_count')->nullable();
            $table->string('ownership_type')->nullable();
            $table->text('sale_reason')->nullable();

            // Status
            $table->string('status')->default(ListingStatus::Draft->value);
            $table->text('rejection_reason')->nullable();

            // Stats
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('favorites_count')->default(0);
            $table->unsignedInteger('responses_count')->default(0);

            // Promotions
            $table->boolean('is_promoted')->default(false);
            $table->timestamp('promoted_until')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->boolean('is_top')->default(false);

            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('category_id');
            $table->index('location_id');
            $table->index('is_promoted');
            $table->index('is_top');
            $table->index('expires_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
