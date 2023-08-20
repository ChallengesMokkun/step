<?php
  namespace App\Function;

  use App\Models\Step;
  use App\Models\User;
  use Image;
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Facades\Log;

  class HelpFunc
  {
    //与えられたものが正の整数であるか調べる
    public static function invalidNumberParam($num): bool
    {
      //数字でない・0以下・小数点を含む場合は無効(trueを返す)
      if(!is_numeric($num) || (int)$num <= 0 || (float)$num - (int)$num !== (float)0){
        return true;
      }
      return false;
    }

    //STEPやページネーションのGETパラメータを調整する
    public static function genarateGetParam($getParams, $from = null){
      if(empty($getParams)){
        return [
          'page' => '?page=',
          'show' => empty($from) ? '' : '?from='.$from,
          'edit' => $from === Step::FROM['mypage'] ? '?from='.Step::FROM['mypage'] : ''
        ];
      }else{
        $getParamString = '?';
        if(isset($getParams['page'])){
          foreach($getParams as $key => $val){
            //pageを最後に結合させる
            if(!empty($val) && $key !== 'page'){
              $getParamString .= $key.'='.$val.'&';
            }
          }
          return [
            'page' => $getParamString.'page=',
            'show' =>  empty($from) ? $getParamString.'page='.$getParams['page'] : $getParamString.'from='.$from.'&page='.$getParams['page'],
            'edit' => $from === Step::FROM['mystep'] ? $getParamString.'page='.$getParams['page'] : ''
          ];
        }else{
          foreach($getParams as $key => $val){
            if(!empty($val)){
              $getParamString .= $key.'='.$val.'&';
            }
          }
          return [
            'page' => $getParamString.'page=',
            'show' => empty($from) ? mb_substr($getParamString, 0, -1, 'UTF-8') : $getParamString.'from='.$from,
            'edit' => $from === Step::FROM['mystep'] ? mb_substr($getParamString, 0, -1, 'UTF-8') : '',
          ];
        }
      }
    }

    //画像を圧縮して保存する
    public static function compressImage($imgFile, $fileName){
      $quality = 50;
      
      $image = Image::make($imgFile)->orientate()->encode('jpg', $quality);
      Storage::put('public/'.User::DIR.'/'.$fileName, $image);
    }


    

    //開発用　ランダムな日付を生成する
    public static function randomDate(){
      $min = strtotime('2023-05-17 08:03:06');
      $max = strtotime('2023-07-05 11:00:00');
  
      $timeStamp = mt_rand($min, $max);

      return date('Y-m-d H:i:s', $timeStamp);
    }
  }