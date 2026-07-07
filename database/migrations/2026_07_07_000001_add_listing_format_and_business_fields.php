<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->string('listing_format')
                ->nullable()
                ->after('type')
                ->comment('sell_business subtype: established_business, asset_sale, real_estate_only');

            $table->string('rent_conditions')->nullable()->after('address');
            $table->text('included_in_deal')->nullable()->after('rent_conditions');
            $table->text('ready_documents')->nullable()->after('included_in_deal');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'listing_format',
                'rent_conditions',
                'included_in_deal',
                'ready_documents',
            ]);
        });
    }
};
