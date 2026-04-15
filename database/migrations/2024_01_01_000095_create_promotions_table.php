<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->comment('promote, top, highlight');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->timestamps();

            $table->index('listing_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('ends_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
