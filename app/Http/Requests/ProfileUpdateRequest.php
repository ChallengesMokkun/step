<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

use App\Rules\NewLineLimit;//テキストエリアの改行数チェックを行うルール
use App\Rules\MaxTextarea;//テキストエリアの最大文字数をチェックするルール

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
          User::COLUMNS['name'] => ['bail', 'required', 'string', 'max:'.User::CONSTANT['nameMax']],
          User::COLUMNS['email'] => ['bail', 'required', 'max:255', 'email:filter,dns', Rule::unique(User::class)->ignore($this->user()->id)],
          User::COLUMNS['pass'] => ['bail', 'required', 'max:255', PasswordRule::defaults(), 'regex:/^[a-zA-Z0-9!?_;:&#%\+\$\^]+$/', 'current_password'],
          User::COLUMNS['pic'] => ['bail', 'image', 'max:'.User::CONSTANT['picMax'], 'nullable'],
          User::COLUMNS['intro'] => ['bail', 'string', new NewLineLimit(User::CONSTANT['introLines']), new MaxTextarea(User::CONSTANT['introMax']), 'nullable'],
        ];
    }
}
