<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Step;

class Category extends Model
{
  use HasFactory;

  public const NUM = 13; //カテゴリー登録数
  
  //Stepモデルとの関連付け
  public function steps(){
    return $this->hasmany(Step::class);
  }

  protected $fillable = [
    'name',
    'kana',
  ];
}
