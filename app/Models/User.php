<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Challenges;
use App\Models\Step;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //定数
    public const CONSTANT = [
      'nameMax' => 20, //ユーザー名の最大文字数
      'introMax' => 300, //自己紹介文の最大文字数
      'picMax' => 10 * 1024, //アイコンの最大容量(KB)
      'introLines' => 21 //自己紹介文の最大改行数
    ];

    //カラム名
    public const COLUMNS = [
      'id' => 'id',
      'name' => 'name',
      'email' => 'email',
      'pass' => 'password',
      'passRe' => 'password_confirmation',
      'pic' => 'pic',
      'intro' => 'introduction',
      'passCurrent' => 'current_password',
      'picBef' => 'before_pic',
      'rememberToken' => 'remember_token',
      'token' => 'token',
      'auth' => 'auth',
      'emailVerifiedAt' => 'email_verified_at'
    ];

    //属性名
    public const ATTR = [
      self::COLUMNS['name'] => 'お名前',
      self::COLUMNS['email'] => 'メールアドレス',
      self::COLUMNS['pass'] => 'パスワード',
      self::COLUMNS['passCurrent'] => '今のパスワード',
      self::COLUMNS['intro'] => '自己紹介文',
      self::COLUMNS['pic'] => 'アイコン',
    ];


    //アイコンを保存するディレクトリ
    public const DIR = 'uploads';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      self::COLUMNS['name'],
      self::COLUMNS['email'],
      self::COLUMNS['pass'],
      self::COLUMNS['pic'],
      self::COLUMNS['intro']
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
      self::COLUMNS['pass'],
      self::COLUMNS['rememberToken'],
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
      self::COLUMNS['emailVerifiedAt'] => 'datetime',
      self::COLUMNS['pass'] => 'hashed',
    ];

    //Challengeモデルとの関連付け
    public function challenges(){
      return $this->hasmany(Challenges::class);
    }

    //Stepモデルとの関連付け
    public function steps(){
      return $this->hasmany(Step::class);
    }
}
