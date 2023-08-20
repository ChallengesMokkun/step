<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\CommonDataComposer;
use App\Http\ViewComposers\StepDataComposer;
use App\Http\ViewComposers\MemberFormComposer;

class StepAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
      View::composers([
        CommonDataComposer::class => ['users.*', 'steps.*', 'inquiry.*'],
        StepDataComposer::class => ['users.mypage', 'steps.*'],
        MemberFormComposer::class => [
          'users.forgot-pass', 
          'users.login', 
          'users.passedit', 
          'users.profedit',
          'users.register',
          'users.reset-pass',
          'users.withdraw',
        ]
      ]);
    }
}
