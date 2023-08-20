<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Carbon\Carbon;

class UserMassGenerate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $chars = 'abcde';
      $names = [
        'Ace',
        'Brian',
        'Carol',
        'Django',
        'Effect'
      ];
      for($i = 0; $i < mb_strlen($chars); $i++){
        User::create([
          'name' => $names[$i],
          'email' => $chars[$i].'@'.$chars[$i].'.com',
          'password' => bcrypt('websteps'),
          'introduction' => 'よろしくお願いします！',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]);
      }
    }
}
