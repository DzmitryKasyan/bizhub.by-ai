<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('reportable');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->string('status')->default('pending')->comment('pending, reviewed, resolved, dismissed');
            $table->foreignId('moderator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('reporter_id');
            $table->index('status');
            $table->index('moderator_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
