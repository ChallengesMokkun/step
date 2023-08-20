<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class PasswordMatch implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    public function __construct($email){
      $this->email = $email;//メールアドレス
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      if(!empty($this->email)){
        $user = User::select(User::COLUMNS['pass'])->where(User::COLUMNS['email'], $this->email)->first()->makeVisible([User::COLUMNS['pass']]);

        if(!empty($user) && password_verify($value, $user->getAttribute(User::COLUMNS['pass']))){
          $fail('違うパスワードを設定してください');
        }
      }
    }
}
