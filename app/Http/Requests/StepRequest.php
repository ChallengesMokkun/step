<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Step;
use App\Rules\NewLineLimit;//テキストエリアの改行数チェックを行うルール
use App\Rules\MaxTextarea;//テキストエリアの最大文字数をチェックするルール

class StepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
      $smallStepRules =  [
        Step::COLUMNS['catId'] => ['bail', 'required', 'integer', 'exists:categories,id'],
        Step::COLUMNS['estimate'] => ['bail', 'required','integer', 'between:'.Step::CONSTANT['estimateMin'].','.Step::CONSTANT['estimateMax']],
        Step::COLUMNS['unitId'] => ['bail', 'required', 'integer', 'between:1,6'],
        Step::COLUMNS['title'] => ['bail', 'required', 'string', new NewLineLimit(Step::CONSTANT['titleLines']), new MaxTextarea(Step::CONSTANT['titleMax'])],
        Step::COLUMNS['phrase'] => ['bail', 'required', 'string', new NewLineLimit(Step::CONSTANT['phraseLines']), new MaxTextarea(Step::CONSTANT['phraseMax'])],
        Step::COLUMNS['supp'] => ['bail', 'string', new NewLineLimit(Step::CONSTANT['suppLines']), new MaxTextarea(Step::CONSTANT['suppMax']), 'nullable'],
        Step::COLUMNS['pubFlg'] => ['bail', 'required', 'boolean'],
        Step::COLUMNS['step1'] => ['bail', 'required', 'string', new NewLineLimit(Step::CONSTANT['stepLines']), new MaxTextarea(Step::CONSTANT['stepMax'])],
        Step::COLUMNS['stepDetail1'] => ['bail', 'required', 'string', new NewLineLimit(Step::CONSTANT['stepDetailLines']), new MaxTextarea(Step::CONSTANT['stepDetailMax'])],
      ];
      for($i = 2; $i <= Step::CONSTANT['maxSmallStep']; $i++){
        $smallStepRules[Step::COLUMNS['step'.$i]] = ['bail', 'required_with:'.Step::COLUMNS['stepDetail'.$i], 'string', new NewLineLimit(Step::CONSTANT['stepLines']), new MaxTextarea(Step::CONSTANT['stepMax']), 'nullable'];
        $smallStepRules[Step::COLUMNS['stepDetail'.$i]] = ['bail', 'required_with:'.Step::COLUMNS['step'.$i], 'string', new NewLineLimit(Step::CONSTANT['stepDetailLines']), new MaxTextarea(Step::CONSTANT['stepDetailMax']), 'nullable'];
      }
      return $smallStepRules;
    }
}
