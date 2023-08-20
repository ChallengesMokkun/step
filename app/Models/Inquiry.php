<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    //定数
    public const CONSTANT = [
      'nameMax' => 20, //名前の最大文字数
      'msgMax' => 500, //用件詳細の最大文字数
      'msgLines' => 29 //用件詳細の最大改行数
    ];

    //カラム名
    public const COLUMNS = [
      'name' =>  'name',
      'email' => 'email',
      'purpose' => 'purpose',
      'msg' => 'msg'
    ];

    //属性名
    public const ATTR = [
      self::COLUMNS['name'] => 'お名前',
      self::COLUMNS['email'] => 'メールアドレス',
      self::COLUMNS['purpose'] => 'ご用件',
      self::COLUMNS['msg'] => 'ご用件の詳細'
    ];


    protected $fillable = [
      self::COLUMNS['name'],
      self::COLUMNS['email'],
      self::COLUMNS['purpose'],
      self::COLUMNS['msg']
    ];
}
