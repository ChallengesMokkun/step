<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\Step;

class TestCreateStep extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $data = [
        [
          "id" => 6,
          "user_id" => 6,
          "title" => "上達させよう！イラスト",
          "phrase" => "イラストを効率よく始めましょう！",
          "category_id" => 8,
          "estimate" => 1,
          "unit_id" => 6,
          "total_step" => 5,
          "supplement" => "最初は難しく感じるかもしれませんが、回数を追うごとに上手くなっていきます！",
          "edited_at" => Carbon::now(),
          "public_flg" => 1,
          "step_1" => "描きたいモチーフを選ぶ",
          "step_detail_1" => "家にある置物や、果物、瓶、スプーンなんでもOKです。自分が学びたい質感をもつモチーフを選ぶとより良いです。",
          "step_2" => "アタリをしっかりとる",
          "step_detail_2" => "まずは全体をしっかり捉えて、アタリ（下書き）を描きましょう。モチーフの全体を観察しましょう。",
          "step_3" => "描き込んでいく",
          "step_detail_3" => "細部を描きこんでいきます。\n初めは最も暗い部分から描き込んでいくと良いでしょう。",
          "step_4" => "描いた絵を公開する",
          "step_detail_4" => "描いた絵を誰かに見せましょう。他の人に評価してもらい、そこから成長につなげることが絵の上達にはとても重要です。",
          "step_5" => "回数をこなす",
          "step_detail_5" => "1から再び繰り返しましょう。\nたまに他の人の絵を見て勉強することも非常に効果的です。",
          "created_at" => Carbon::now(),
          "updated_at" => Carbon::now(),
        ],
        [
          "id" => 15,
          "user_id" => 6,
          "category_id" => 8,
          "title" => "上達させよう！イラスト",
          "phrase" => "イラストを効率よく始めましょう！",
          "estimate" => 1,
          "unit_id" => 6,
          "total_step" => 5,
          "supplement" => "最初は難しく感じるかもしれませんが、回数を追うごとに上手くなっていきます！",
          "edited_at" => Carbon::now(),
          "public_flg" => 0,
          "step_1" => "描きたいモチーフを選ぶ",
          "step_detail_1" => "家にある置物や、果物、瓶、スプーンなんでもOKです。自分が学びたい質感をもつモチーフを選ぶとより良いです。",
          "step_2" => "アタリをしっかりとる",
          "step_detail_2" => "まずは全体をしっかり捉えて、アタリ（下書き）を描きましょう。モチーフの全体を観察しましょう。",
          "step_3" => "描き込んでいく",
          "step_detail_3" => "細部を描きこんでいきます。\n初めは最も暗い部分から描き込んでいくと良いでしょう。",
          "step_4" => "描いた絵を公開する",
          "step_detail_4" => "描いた絵を誰かに見せましょう。他の人に評価してもらい、そこから成長につなげることが絵の上達にはとても重要です。",
          "step_5" => "回数をこなす",
          "step_detail_5" => "1から再び繰り返しましょう。\nたまに他の人の絵を見て勉強することも非常に効果的です。",
          "created_at" => Carbon::now(),
          "updated_at" => Carbon::now(),
        ],
        [
          "id" => 23,
          "user_id" => 6,
          "title" => "上達させよう！イラスト",
          "phrase" => "イラストを効率よく始めましょう！",
          "category_id" => 8,
          "estimate" => 1,
          "unit_id" => 6,
          "total_step" => 5,
          "supplement" => "最初は難しく感じるかもしれませんが、回数を追うごとに上手くなっていきます！",
          "edited_at" => Carbon::now(),
          "public_flg" => 1,
          "step_1" => "描きたいモチーフを選ぶ",
          "step_detail_1" => "家にある置物や、果物、瓶、スプーンなんでもOKです。自分が学びたい質感をもつモチーフを選ぶとより良いです。",
          "step_2" => "アタリをしっかりとる",
          "step_detail_2" => "まずは全体をしっかり捉えて、アタリ（下書き）を描きましょう。モチーフの全体を観察しましょう。",
          "step_3" => "描き込んでいく",
          "step_detail_3" => "細部を描きこんでいきます。\n初めは最も暗い部分から描き込んでいくと良いでしょう。",
          "step_4" => "描いた絵を公開する",
          "step_detail_4" => "描いた絵を誰かに見せましょう。他の人に評価してもらい、そこから成長につなげることが絵の上達にはとても重要です。",
          "step_5" => "回数をこなす",
          "step_detail_5" => "1から再び繰り返しましょう。\nたまに他の人の絵を見て勉強することも非常に効果的です。",
          "created_at" => Carbon::now(),
          "updated_at" => Carbon::now(),
        ],
      ];

      for($i = 0; $i < count($data); $i++){
        $step = new Step;
        $step->forceFill($data[$i])->save();
      }
    }
}
