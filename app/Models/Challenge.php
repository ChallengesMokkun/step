<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Step;

class Challenge extends Model
{
  use HasFactory;

  public const COLUMNS = [
    'id' => 'id',
    'userId' => 'user_id',
    'stepId' => 'step_id',
    'current' => 'current_step',
    'clearFlg' => 'clear_flg',
    'numChangeFlg' => 'num_change_flg',
    'retryFlg' => 'retry_flg',
    'latestAt' => 'latest_at',
    'createdAt' => 'created_at',
    'updatedAt' => 'updated_at',
  ];

  protected $fillable = [
    self::COLUMNS['userId'],
    self::COLUMNS['stepId'],
    self::COLUMNS['current'],
    self::COLUMNS['clearFlg'],
    self::COLUMNS['numChangeFlg'],
    self::COLUMNS['retryFlg'],
    self::COLUMNS['latestAt'],
    self::COLUMNS['createdAt'],
  ];

  //Userモデルとの関連付け
  public function user(){
    return $this->belongsTo(User::class);
  }
  //Stepモデルとの関連付け
  public function step(){
    return $this->belongsTo(Step::class);
  }
}
