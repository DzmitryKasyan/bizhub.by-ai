<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Exchange rates — refresh every hour
Schedule::command('exchange:update')->hourly();

// Recount active listings for categories and types
Schedule::command('listings:recount-counters')->hourly();
