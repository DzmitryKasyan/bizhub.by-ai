<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nda_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('signed_at');
            $table->string('ip_address')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
            $table->unique(['listing_id', 'buyer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nda_signatures');
    }
};
