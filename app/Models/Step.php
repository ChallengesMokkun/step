<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\User;
use App\Models\Category;
use App\Models\Challenges;

class Step extends Model
{
  use HasFactory;

  //定数
  public const CONSTANT = [
    'maxSmallStep' => 10, //子STEPの最大数
    'perPage' => 12, //1ページあたりのSTEP取得数(ページネーション)
    'perDisp' => 6, //STEP取得数(マイページ・プロフィールページ)
    'titleMax' => 40, //STEPタイトルの最大文字数
    'phraseMax' => 50, //キャッチコピーの最大文字数
    'stepMax' => 40, //子STEPタイトルの最大文字数
    'stepDetailMax' => 500, //子STEP説明の最大文字数
    'suppMax' => 500, //補足・メッセージの最大文字数
    'estimateMin' => 1, //達成目安の最小値
    'estimateMax' => 999, //達成目安の最大値
    'titleLines' => 2, //STEPタイトルの最大改行数
    'phraseLines' => 2, //キャッチコピーの最大改行数
    'stepLines' => 2, //子STEPタイトルの最大改行数
    'stepDetailLines' => 28, //子STEP説明の最大改行数
    'suppLines' => 29 //補足・メッセージの最大改行数
  ];

  //カラム名
  public const COLUMNS = [
    'id' => 'id',
    'userId' => 'user_id',
    'catId' => 'category_id',
    'estimate' => 'estimate',
    'unitId' => 'unit_id',
    'title' => 'title',
    'phrase' => 'phrase',
    'supp' => 'supplement',
    'pubFlg' => 'public_flg',
    'total' => 'total_step',
    'editedAt' => 'edited_at',
    'stepEditUrl' => 'step_edit_url',
    'stepShowUrl' => 'step_show_url',
    'stepsCategoryUrl' => 'steps_category_url',
    'memberProfileUrl' => 'member_profile_url',
    'memberProfilePic' => 'member_profile_pic',
    'step1' => 'step_1',
    'stepDetail1' => 'step_detail_1',
    'step2' => 'step_2',
    'stepDetail2' => 'step_detail_2',
    'step3' => 'step_3',
    'stepDetail3' => 'step_detail_3',
    'step4' => 'step_4',
    'stepDetail4' => 'step_detail_4',
    'step5' => 'step_5',
    'stepDetail5' => 'step_detail_5',
    'step6' => 'step_6',
    'stepDetail6' => 'step_detail_6',
    'step7' => 'step_7',
    'stepDetail7' => 'step_detail_7',
    'step8' => 'step_8',
    'stepDetail8' => 'step_detail_8',
    'step9' => 'step_9',
    'stepDetail9' => 'step_detail_9',
    'step10' => 'step_10',
    'stepDetail10' => 'step_detail_10',
    'keyword' => 'keyword'
  ];

  //属性名
  public const ATTR = [
    self::COLUMNS['catId'] => 'カテゴリー',
    self::COLUMNS['estimate'] => '達成目安',
    self::COLUMNS['unitId'] => '単位',
    self::COLUMNS['title'] => 'タイトル',
    self::COLUMNS['phrase'] => 'キャッチコピー',
    self::COLUMNS['supp'] => '補足・メッセージ',
    self::COLUMNS['pubFlg'] => '公開設定',
    self::COLUMNS['step1'] => 'タイトル',
    self::COLUMNS['stepDetail1'] => '説明',
    self::COLUMNS['step2'] => 'タイトル',
    self::COLUMNS['stepDetail2'] => '説明',
    self::COLUMNS['step3'] => 'タイトル',
    self::COLUMNS['stepDetail3'] => '説明',
    self::COLUMNS['step4'] => 'タイトル',
    self::COLUMNS['stepDetail4'] => '説明',
    self::COLUMNS['step5'] => 'タイトル',
    self::COLUMNS['stepDetail5'] => '説明',
    self::COLUMNS['step6'] => 'タイトル',
    self::COLUMNS['stepDetail6'] => '説明',
    self::COLUMNS['step7'] => 'タイトル',
    self::COLUMNS['stepDetail7'] => '説明',
    self::COLUMNS['step8'] => 'タイトル',
    self::COLUMNS['stepDetail8'] => '説明',
    self::COLUMNS['step9'] => 'タイトル',
    self::COLUMNS['stepDetail9'] => '説明',
    self::COLUMNS['step10'] => 'タイトル',
    self::COLUMNS['stepDetail10'] => '説明',
  ];

  //STEP詳細からの戻り先
  public const FROM = [
    'profile' => 'profile',
    'challenge' => 'challenge',
    'mystep' => 'mystep',
    'mypage' => 'mypage',
  ];

  //時間の単位
  public const UNITS = [
    ['id' => 1, 'name' => '分'],
    ['id' => 2, 'name' => '時間'],
    ['id' => 3, 'name' => '日'],
    ['id' => 4, 'name' => '週間'],
    ['id' => 5, 'name' => 'ヶ月'],
    ['id' => 6, 'name' => '年']
  ];

  //STEP詳細URLを返す
  public function stepShowUrl(): Attribute
  {
    return new Attribute(
      get: fn () => route('step.show', ['id' => $this->{self::COLUMNS['id']}])
    );
  }

  //カテゴリーでSTEP検索するURLを返す
  public function stepsCategoryUrl(): Attribute
  {
    return new Attribute(
      get: fn () => route('step.index', ['category_id' => $this->{self::COLUMNS['catId']}])
    );
  }

  //STEPを登録したユーザーの、プロフィールページURLを返す
  public function memberProfileUrl(): Attribute
  {
    return new Attribute(
      get: fn () => route('profile.show', ['id' => $this->{self::COLUMNS['userId']}])
    );
  }

  //STEPを登録したユーザーの、アイコンURLを返す
  public function memberProfilePic(): Attribute
  {
    return new Attribute(
      get: fn () => !empty($this->{User::COLUMNS['pic']}) ? asset($this->{User::COLUMNS['pic']}) : asset('null-user.png')
    );
  }
  //STEP編集URLを返す
  public function stepEditUrl(): Attribute
  {
    return new Attribute(
      get: fn () => route('step.edit', ['id' => $this->{self::COLUMNS['id']}])
    );
  }

  protected $appends = [
    self::COLUMNS['stepShowUrl'],
    self::COLUMNS['stepsCategoryUrl']
  ];


  protected $fillable = [
    self::COLUMNS['userId'],
    self::COLUMNS['catId'],
    self::COLUMNS['estimate'],
    self::COLUMNS['unitId'],
    self::COLUMNS['title'],
    self::COLUMNS['phrase'],
    self::COLUMNS['supp'],
    self::COLUMNS['pubFlg'],
    self::COLUMNS['total'],
    self::COLUMNS['editedAt'],
    self::COLUMNS['step1'],
    self::COLUMNS['stepDetail1'],
    self::COLUMNS['step2'],
    self::COLUMNS['stepDetail2'],
    self::COLUMNS['step3'],
    self::COLUMNS['stepDetail3'],
    self::COLUMNS['step4'],
    self::COLUMNS['stepDetail4'],
    self::COLUMNS['step5'],
    self::COLUMNS['stepDetail5'],
    self::COLUMNS['step6'],
    self::COLUMNS['stepDetail6'],
    self::COLUMNS['step7'],
    self::COLUMNS['stepDetail7'],
    self::COLUMNS['step8'],
    self::COLUMNS['stepDetail8'],
    self::COLUMNS['step9'],
    self::COLUMNS['stepDetail9'],
    self::COLUMNS['step10'],
    self::COLUMNS['stepDetail10'],
  ];

  //Categoryモデルとの関連付け
  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  //Challengeモデルとの関連付け
  public function challenges(){
    return $this->hasmany(Challenges::class);
  }

  //Userモデルとの関連付け
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
