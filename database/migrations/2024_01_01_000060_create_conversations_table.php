<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('participant_one_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('participant_two_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index('participant_one_id');
            $table->index('participant_two_id');
            $table->index('listing_id');
            $table->index('last_message_at');
            $table->unique(['participant_one_id', 'participant_two_id', 'listing_id'], 'unique_conversation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
