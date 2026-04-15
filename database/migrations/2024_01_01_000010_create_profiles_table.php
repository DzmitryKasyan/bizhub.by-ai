<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type')->nullable()->comment('entrepreneur, investor, broker');
            $table->unsignedSmallInteger('experience_years')->nullable();
            $table->decimal('investment_range_min', 15, 2)->nullable();
            $table->decimal('investment_range_max', 15, 2)->nullable();
            $table->json('industries')->nullable()->comment('Array of industry slugs');
            $table->json('regions')->nullable()->comment('Array of region IDs');
            $table->json('social_links')->nullable()->comment('Keys: telegram, linkedin, facebook, instagram, website');
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
