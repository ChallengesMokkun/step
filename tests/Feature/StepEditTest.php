<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('stepRegister screen cannot be rendered1', function () {
  $response = $this->get(route('step.edit', ['id' => 1]));
  $response->assertRedirect(route('login'));
  $this->assertGuest();
});

test('stepRegister screen cannot be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => 'a']));
  $response->assertRedirect(route('mystep'));
});

test('stepRegister screen cannot be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => -1]));
  $response->assertRedirect(route('mystep'));
});

test('stepRegister screen cannot be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => 3.5]));
  $response->assertRedirect(route('mystep'));
});

test('stepRegister screen cannot be rendered5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => 1]));
  $response->assertRedirect(route('mystep'));
});

test('stepRegister screen cannot be rendered6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => 6]));
  $response->assertRedirect(route('mystep'));
});

test('stepRegister screen can be rendered', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.edit', ['id' => 6]));
  $response->assertStatus(200);

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.edit');
});

test('stepEdit success', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    "category_id" => 5,
    "title" => "転職を始める",
    "phrase" => "転職活動を始める前に、考えるべきことがあります",
    "estimate" => 3,
    "unit_id" => 5,
    "public_flg" => 1,
    "step_1" => "自分の強み・スキルを洗い出す",
    "step_detail_1" => "自己分析をしましょう。\nこれまでの経験、身に付けたスキルや資格、苦労した点や工夫した点などを書き出します。\n自分の長所や短所を客観的に把握することで、向いている仕事を選ぶことや自己PR、面接の対策にも役立ちます。",
    "step_2" => "転職の軸を決める",
    "step_detail_2" => "仕事をするうえで譲れないポイントを決めます。\n何を大切にして仕事をしたいか、譲れないポイントを決めることで迷う場面が減るでしょう。",
    "step_3" => "情報収集する",
    "step_detail_3" => "企業研究・業界研究をしましょう。\n希望の企業が決まっている場合、その企業の公式サイトに細かく目を通しましょう。企業理念や経営方針などを読み込み、自分の求めているものと合っているか確認します。\n企業が明確に決まっていない場合は、行きたい業界について詳しく調べると良いでしょう。",
  ];

  $response = $this->put(route('step.edit', ['id' => 15]), $data);

  //送信に成功
  $response->assertStatus(302);

  $this->assertDatabaseHas('steps', [
    "id" => 15,
    "title" => $data['title'],
    "public_flg" => 1
  ]);

  $response->assertSessionHas('status', 'STEPを更新しました');
});

test('stepEdit id invalid1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    "category_id" => 5,
    "title" => "転職を始める",
    "phrase" => "転職活動を始める前に、考えるべきことがあります",
    "estimate" => 3,
    "unit_id" => 5,
    "public_flg" => 1,
    "step_1" => "自分の強み・スキルを洗い出す",
    "step_detail_1" => "自己分析をしましょう。\nこれまでの経験、身に付けたスキルや資格、苦労した点や工夫した点などを書き出します。\n自分の長所や短所を客観的に把握することで、向いている仕事を選ぶことや自己PR、面接の対策にも役立ちます。",
    "step_2" => "転職の軸を決める",
    "step_detail_2" => "仕事をするうえで譲れないポイントを決めます。\n何を大切にして仕事をしたいか、譲れないポイントを決めることで迷う場面が減るでしょう。",
    "step_3" => "情報収集する",
    "step_detail_3" => "企業研究・業界研究をしましょう。\n希望の企業が決まっている場合、その企業の公式サイトに細かく目を通しましょう。企業理念や経営方針などを読み込み、自分の求めているものと合っているか確認します。\n企業が明確に決まっていない場合は、行きたい業界について詳しく調べると良いでしょう。",
  ];

  $response = $this->put(route('step.edit', ['id' => 'a']), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertSessionHas('status', '不正な操作です');
});

test('stepEdit id invalid2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    "category_id" => 5,
    "title" => "転職を始める",
    "phrase" => "転職活動を始める前に、考えるべきことがあります",
    "estimate" => 3,
    "unit_id" => 5,
    "public_flg" => 1,
    "step_1" => "自分の強み・スキルを洗い出す",
    "step_detail_1" => "自己分析をしましょう。\nこれまでの経験、身に付けたスキルや資格、苦労した点や工夫した点などを書き出します。\n自分の長所や短所を客観的に把握することで、向いている仕事を選ぶことや自己PR、面接の対策にも役立ちます。",
    "step_2" => "転職の軸を決める",
    "step_detail_2" => "仕事をするうえで譲れないポイントを決めます。\n何を大切にして仕事をしたいか、譲れないポイントを決めることで迷う場面が減るでしょう。",
    "step_3" => "情報収集する",
    "step_detail_3" => "企業研究・業界研究をしましょう。\n希望の企業が決まっている場合、その企業の公式サイトに細かく目を通しましょう。企業理念や経営方針などを読み込み、自分の求めているものと合っているか確認します。\n企業が明確に決まっていない場合は、行きたい業界について詳しく調べると良いでしょう。",
  ];

  $response = $this->put(route('step.edit', ['id' => -5]), $data);

  //送信に成功
  $response->assertStatus(302);


  $response->assertSessionHas('status', '不正な操作です');
});

test('stepEdit id invalid3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    "category_id" => 5,
    "title" => "転職を始める",
    "phrase" => "転職活動を始める前に、考えるべきことがあります",
    "estimate" => 3,
    "unit_id" => 5,
    "public_flg" => 1,
    "step_1" => "自分の強み・スキルを洗い出す",
    "step_detail_1" => "自己分析をしましょう。\nこれまでの経験、身に付けたスキルや資格、苦労した点や工夫した点などを書き出します。\n自分の長所や短所を客観的に把握することで、向いている仕事を選ぶことや自己PR、面接の対策にも役立ちます。",
    "step_2" => "転職の軸を決める",
    "step_detail_2" => "仕事をするうえで譲れないポイントを決めます。\n何を大切にして仕事をしたいか、譲れないポイントを決めることで迷う場面が減るでしょう。",
    "step_3" => "情報収集する",
    "step_detail_3" => "企業研究・業界研究をしましょう。\n希望の企業が決まっている場合、その企業の公式サイトに細かく目を通しましょう。企業理念や経営方針などを読み込み、自分の求めているものと合っているか確認します。\n企業が明確に決まっていない場合は、行きたい業界について詳しく調べると良いでしょう。",
  ];

  $response = $this->put(route('step.edit', ['id' => 6.8]), $data);

  //送信に成功
  $response->assertStatus(302);
  


  $response->assertSessionHas('status', '不正な操作です');
});

test('stepEdit id invalid4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    "category_id" => 5,
    "title" => "転職を始める",
    "phrase" => "転職活動を始める前に、考えるべきことがあります",
    "estimate" => 3,
    "unit_id" => 5,
    "public_flg" => 1,
    "step_1" => "自分の強み・スキルを洗い出す",
    "step_detail_1" => "自己分析をしましょう。\nこれまでの経験、身に付けたスキルや資格、苦労した点や工夫した点などを書き出します。\n自分の長所や短所を客観的に把握することで、向いている仕事を選ぶことや自己PR、面接の対策にも役立ちます。",
    "step_2" => "転職の軸を決める",
    "step_detail_2" => "仕事をするうえで譲れないポイントを決めます。\n何を大切にして仕事をしたいか、譲れないポイントを決めることで迷う場面が減るでしょう。",
    "step_3" => "情報収集する",
    "step_detail_3" => "企業研究・業界研究をしましょう。\n希望の企業が決まっている場合、その企業の公式サイトに細かく目を通しましょう。企業理念や経営方針などを読み込み、自分の求めているものと合っているか確認します。\n企業が明確に決まっていない場合は、行きたい業界について詳しく調べると良いでしょう。",
  ];

  $response = $this->put(route('step.edit', ['id' => 100]), $data);

  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('mystep'));

  $response->assertSessionHas('status', '不正な操作です');
});

test('stepEdit empty', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [];

  $response = $this->put(route('step.edit', ['id' => 15]), $data);

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

test('too many1 / out of range1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

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

  $response = $this->put(route('step.edit', ['id' => 15]), $data);

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
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

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

  $response = $this->put(route('step.edit', ['id' => 15]), $data);

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
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

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

  $response = $this->put(route('step.edit', ['id' => 15]), $data);

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