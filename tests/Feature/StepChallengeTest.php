<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Database\Seeders\TestCreateChallenges;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('challenge failure1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $response = $this->post(route('step.challenge', ['id' => 6]));
  $response->assertRedirect(route('login'));
});

test('challenge failure2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->post(route('step.challenge', ['id' => -1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->post(route('step.challenge', ['id' => 3.5]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->post(route('step.challenge', ['id' => 'あ']));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show_small', ['id' => 6, 'step' => 3]))->post(route('step.challenge', ['id' => 'あ']));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 3]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show_small', ['id' => 6, 'step' => 3]))->post(route('step.challenge', ['id' => -6]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 3]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show_small', ['id' => 6, 'step' => 3]))->post(route('step.challenge', ['id' => 5.8]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 3]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('challenge failure8', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('step.challenge', ['id' => 1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', 'チャレンジできませんでした');
});

test('challenge failure9', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('step.challenge', ['id' => 15]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', 'チャレンジできませんでした');
});

test('challenge failure10', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show_small', ['id' => 6, 'step' => 3]))->post(route('step.challenge', ['id' => 6]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 3]));
  $response->assertSessionHas('status', 'チャレンジ中です');
});

test('challenge failure11', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->post(route('step.challenge', ['id' => 6]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', 'チャレンジ中です');
});


test('challenge success1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('step.challenge', ['id' => 6]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'step_id' => 6,
  ]);

  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 1]));
  $response->assertSessionHas('status', 'チャレンジしました');
});

test('challenge success2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('step.challenge', ['id' => 15]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'step_id' => 15,
  ]);
  $response->assertRedirect(route('step.show_small', ['id' => 15, 'step' => 1]));
  $response->assertSessionHas('status', 'チャレンジしました');
});

test('challenge success3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('step.challenge', ['id' => 23]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'step_id' => 23,
    'retry_flg' => 1
  ]);
  $response->assertRedirect(route('step.show_small', ['id' => 23, 'step' => 1]));
  $response->assertSessionHas('status', 'チャレンジしました');
});