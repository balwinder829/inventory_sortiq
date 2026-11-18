<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule::command('notify:lowstock')
//     ->cron('0 6,12,18,22 * * *')
//     ->timezone('Asia/Kolkata')
//     ->withoutOverlapping()
//     ->runInBackground();

// 

Schedule::command('notify:lowstock')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping()
    ->runInBackground()
    ->cron('0 9,15,21 * * *');


