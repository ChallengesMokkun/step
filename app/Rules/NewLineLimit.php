<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Function\HelpFunc;

//改行数をチェックするルール 明らかに多すぎる改行を防ぐ
//表示のための厳密な行数は、SCSSで制限している
class NewLineLimit implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct(int $newLinesNum){
      $this->newLinesNum = $newLinesNum;//最大改行数
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      if(!empty($this->newLinesNum) && !HelpFunc::invalidNumberParam($this->newLinesNum)){
        if(!empty($value) && substr_count($value, "\r\n") >= $this->newLinesNum){
          $fail('改行を減らしてください。');
        }
      }
    }
}
