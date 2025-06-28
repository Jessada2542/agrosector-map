<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Asia/Bangkok'); // บังคับ PHP ใช้เวลาประเทศไทย
        Carbon::setLocale('th'); // ตั้ง locale Carbon เป็นภาษาไทย (optional)
    }
}
