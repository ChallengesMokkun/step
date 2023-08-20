<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\Challenge;

class TestCreateChallenges extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $data = [
        [
          'id' => 1,
          'user_id' => 6,
          'step_id' => 6,
          'current_step' => 3,
          'clear_flg' => 0,
          'num_change_flg' => 0,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 2,
          'user_id' => 7,
          'step_id' => 6,
          'current_step' => 8,
          'clear_flg' => 0,
          'num_change_flg' => 1,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 3,
          'user_id' => 7,
          'step_id' => 15,
          'current_step' => 1,
          'clear_flg' => 0,
          'num_change_flg' => 0,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 4,
          'user_id' => 6,
          'step_id' => 15,
          'current_step' => 2,
          'clear_flg' => 0,
          'num_change_flg' => 0,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 5,
          'user_id' => 7,
          'step_id' => 23,
          'current_step' => 4,
          'clear_flg' => 0,
          'num_change_flg' => 0,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ],
        [
          'id' => 6,
          'user_id' => 6,
          'step_id' => 23,
          'current_step' => 5,
          'clear_flg' => 1,
          'num_change_flg' => 0,
          'retry_flg' => 0,
          'latest_at' => Carbon::now(),
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]
      ];

      for($i = 0; $i < count($data); $i++){
        $challenge = new Challenge;
        $challenge->forceFill($data[$i])->save();
      }
    }
}
