<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('price_on_request')->default(false)->after('price_negotiable');
            $table->boolean('is_representative')->default(false)->after('is_top');
            $table->string('representative_note')->nullable()->after('is_representative');
            $table->boolean('address_public')->default(false)->after('representative_note');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['price_on_request', 'is_representative', 'representative_note', 'address_public']);
        });
    }
};
