<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;

test('stepRegister screen cannot be rendered', function () {
  $response = $this->get(route('step.register'));
  $response->assertRedirect(route('login'));
  $this->assertGuest();
});

test('stepRegister screen can be rendered', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $response = $this->get(route('step.register'));

  $response->assertStatus(200);

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.new');
});



test('empty', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $data = [];

  $response = $this->post(route('step.register'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    "category_id" => '必ず入力してください。',
    "title" => '必ず入力してください。',
    "phrase" => '必ず入力してください。',
    "estimate" => '必ず入力してください。',
    "unit_id" => '必ず入力してください。',
    "public_flg" => '必ず入力してください。',
    "step_1" => '必ず入力してください。',
    "step_detail_1" => '必ず入力してください。',
  ]);
});

test('stepRegister success', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $data = [
    "category_id" => 8,
    "title" => "上達させよう！イラスト",
    "phrase" => "イラストを効率よく始めましょう！",
    "estimate" => 1,
    "unit_id" => 6,
    "supplement" => "最初は難しく感じるかもしれませんが、回数を追うごとに上手くなっていきます！",
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
  ];

  $response = $this->post(route('step.register'), $data);

  //送信に成功
  $response->assertStatus(302);

  $this->assertDatabaseHas('steps', [
    "user_id" => $user->id
  ]);

  $response->assertRedirect(route('mystep'));
  $response->assertSessionHas('status', 'STEPを登録しました');
});

test('too many1 / out of range1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $data = [
    "category_id" => 8888,
    "title" => 'a'.str_repeat("\r\n", 3).'b',
    "phrase" => 'a'.str_repeat("\r\n", 3).'b',
    "estimate" => 9999,
    "unit_id" => 9999,
    "supplement" => 'a'.str_repeat("\r\n", 30).'b',
    "public_flg" => 8,
    "step_1" => 'a'.str_repeat("\r\n", 3).'b',
    "step_detail_1" => 'a'.str_repeat("\r\n", 29).'b',
    "step_5" => 'a'.str_repeat("\r\n", 3).'b',
    "step_detail_5" => 'a'.str_repeat("\r\n", 29).'b',
  ];

  $response = $this->post(route('step.register'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    "category_id" => '不正な値です。',
    "title" => '改行を減らしてください。',
    "phrase" => '改行を減らしてください。',
    "estimate" => '不正な値です。',
    "unit_id" => '不正な値です。',
    "public_flg" => '公開設定は、いずれかを選択してください。',
    "step_1" => '改行を減らしてください。',
    "step_detail_1" => '改行を減らしてください。',
    "step_5" => '改行を減らしてください。',
    "step_detail_5" => '改行を減らしてください。',
  ]);
});


test('too many2 / out of range2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $data = [
    "category_id" => -1,
    "title" => str_repeat('a', 41),
    "phrase" => str_repeat('a', 51),
    "estimate" => -9999,
    "unit_id" => -9999,
    "supplement" => str_repeat('a', 501),
    "public_flg" => -8,
    "step_1" => str_repeat('a', 41),
    "step_detail_1" => str_repeat('a', 501),
    "step_5" => str_repeat('a', 41),
    "step_detail_5" => str_repeat('a', 501),
  ];

  $response = $this->post(route('step.register'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    "category_id" => '不正な値です。',
    "title" => '40文字以下で入力してください。',
    "phrase" => '50文字以下で入力してください。',
    "estimate" => '不正な値です。',
    "unit_id" => '不正な値です。',
    "public_flg" => '公開設定は、いずれかを選択してください。',
    "step_1" => '40文字以下で入力してください。',
    "step_detail_1" => '500文字以下で入力してください。',
    "step_5" => '40文字以下で入力してください。',
    "step_detail_5" => '500文字以下で入力してください。',
  ]);
});

test('type error1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $this->seed(TestCreateCategories::class);

  $data = [
    "category_id" => 'a',
    "estimate" => 'b',
    "unit_id" => 'c',
    "public_flg" => 'd',
    "title" => str_repeat('a', 40),
    "phrase" => str_repeat('a', 50),
    "supplement" => str_repeat('a', 500),
    "step_1" => str_repeat('a', 40),
    "step_detail_1" => str_repeat('a', 500),
    "step_5" => str_repeat('a', 40),
    "step_detail_5" => str_repeat('a', 500),
  ];

  $response = $this->post(route('step.register'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertValid([
    "title",
    "phrase",
    "step_1",
    "step_detail_1",
    "step_5",
    "step_detail_5",
  ]);
  $response->assertInvalid([
    "category_id" => '不正な値です。',
    "estimate" => '不正な値です。',
    "unit_id" => '不正な値です。',
    "public_flg" => '公開設定は、いずれかを選択してください。',
  ]);
});
