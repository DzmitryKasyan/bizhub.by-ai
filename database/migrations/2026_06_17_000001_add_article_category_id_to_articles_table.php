<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('article_category_id')
                ->nullable()
                ->constrained('article_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('articles')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropForeign(['article_category_id']);
                $table->dropColumn('article_category_id');
            });
        }
    }
};
