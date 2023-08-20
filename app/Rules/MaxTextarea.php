<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Function\HelpFunc;

//テキストエリアの最大文字数をチェックする 改行を含めずにチェックする
class MaxTextarea implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct(int $maxCharNum){
      $this->maxCharNum = $maxCharNum;//最大文字数
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      if(!empty($this->maxCharNum) && !HelpFunc::invalidNumberParam($this->maxCharNum)){
        $value = preg_replace('/\r\n/', '', $value);
        if(mb_strlen($value) > $this->maxCharNum){
          $fail($this->maxCharNum.'文字以下で入力してください。');
        }
      }
    }
}
