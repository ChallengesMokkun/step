<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Carbon\Carbon;

class TestCreateUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $data = [
        [
          'id' => 6,
          'name' => 'Mokkun',
          'email' => 'challenges.mokkun11@gmail.com',
          'password' => bcrypt('password'),
          'introduction' => 'よろしくお願いします！',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 7,
          'name' => 'ggg',
          'email' => 'ggg@gmail.com',
          'password' => bcrypt('password'),
          'introduction' => 'よろしくお願いします！',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 24,
          'name' => 'xxx',
          'email' => 'xxx@gmail.com',
          'password' => bcrypt('password'),
          'introduction' => 'よろしくお願いします！',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
      ];

      for($i = 0; $i < count($data); $i++){
        $user = new User;
        $user->forceFill($data[$i])->save();
      }
    }
}
