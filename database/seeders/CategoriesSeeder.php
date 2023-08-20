<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use Carbon\Carbon;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Category::create([
        'name' => '育児',
        'kana' => 'いくじ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => 'お金',
        'kana' => 'おかね',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '家事全般',
        'kana' => 'かじぜんぱん',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '考え方',
        'kana' => 'かんがえかた',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => 'キャリア',
        'kana' => 'きゃりあ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '語学',
        'kana' => 'ごがく',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '仕事術',
        'kana' => 'しごとじゅつ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '趣味全般',
        'kana' => 'しゅみぜんぱん',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => 'スポーツ',
        'kana' => 'すぽーつ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '生活習慣',
        'kana' => 'せいかつしゅうかん',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => 'プログラミング',
        'kana' => 'ぷろぐらみんぐ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => '勉強全般',
        'kana' => 'べんきょうぜんぱん',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      Category::create([
        'name' => 'その他',
        'kana' => 'ソノタ',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
    }
}
