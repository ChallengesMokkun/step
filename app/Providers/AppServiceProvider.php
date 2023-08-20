<?php

namespace App\Providers;

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
      if(config('app.env') !== 'production'){
        \DB::listen(function($query){
          \Log::info("Query Time: {$query->time}s] $query->sql");
        });
      }
    }
}
