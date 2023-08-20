<?php
  namespace App\Http\ViewComposers;

  use \Illuminate\Contracts\View\View;

  use App\Models\User;

  //ビューに渡すデータ(どのビューでも共通で使うもの)
  class MemberFormComposer {
    public function compose(View $view){
      $view->with('memberFormData', [
        'columns' => User::COLUMNS,
        'constant' => User::CONSTANT
      ]);
    }
  }