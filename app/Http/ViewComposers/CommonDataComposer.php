<?php
  namespace App\Http\ViewComposers;

  use \Illuminate\Contracts\View\View;

  //ビューに渡すデータ(どのビューでも共通で使うもの)
  class CommonDataComposer {
    public function compose(View $view){
      $view->with('commonData', [
        'url' => [
          'login' => route('login'),
          'userRegister' => route('user.register'),
          'passForgot' => route('password.forgot'),
          'mypage' => route('mypage'),
          'passEdit' => route('password.edit'),
          'passForgot' => route('password.forgot'),
          'tos' => route('tos'),
          'privacy' => route('privacy'),
          'profEdit' => route('profile.edit'),
          'passResetForm' => route('password.store'),
          'withdraw' => route('withdraw'),
          'stepRegister' => route('step.register'),
          'myStep' => route('mystep'),
          'challenges' => route('challenges'),
          'inquiry' => route('inquiry'),
          'stepIndex' => route('step.index'),
          'nullUser' => asset('null-user.png'),
        ],
        'csrf' => csrf_token(),
        'appName' => config('app.name'),
        'catchphrase' => config('app.catchphrase')
      ]);
    }
  }