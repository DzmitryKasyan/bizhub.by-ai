<?php

use App\Enums\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->comment('subscription, promotion, top_listing, highlight');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default(Currency::BYN->value);
            $table->string('status')->default('pending')->comment('pending, completed, failed, refunded');
            $table->string('payment_method')->nullable()->comment('erip, bepaid, stripe, manual');
            $table->string('payment_id')->nullable()->comment('External payment provider ID');
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
