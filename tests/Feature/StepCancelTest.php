<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Database\Seeders\TestCreateChallenges;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('cancel failure1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $response = $this->delete(route('step.challenge', ['id' => 6]));
  $response->assertRedirect(route('login'));
});

test('cancel failure2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->delete(route('step.challenge', ['id' => -1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('cancel failure3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->delete(route('step.challenge', ['id' => 3.5]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});

test('cancel failure4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->delete(route('step.challenge', ['id' => 'あ']));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '不正な操作です');
});


test('cancel failure5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->delete(route('step.challenge', ['id' => 1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', 'キャンセルできませんでした');
});

test('cancel failure6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->delete(route('step.challenge', ['id' => 15]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', 'キャンセルできませんでした');
});

test('cancel failure7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->delete(route('step.challenge', ['id' => 6]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', 'チャレンジしていません');
});

test('cancel failure8', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 23]))->delete(route('step.challenge', ['id' => 23]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 23]));
  $response->assertSessionHas('status', '既にクリアしています');
});


test('cancel success', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->from(route('step.show', ['id' => 6]))->delete(route('step.challenge', ['id' => 6]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseMissing('challenges', [
    'step_id' => 6,
    'user_id' => 6
  ]);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', 'チャレンジをキャンセルしました');
});