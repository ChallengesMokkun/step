<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Database\Seeders\TestCreateChallenges;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('clear failure1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 1]));
  $response->assertRedirect(route('login'));
});

test('clear failure2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => -1, 'step' => -6]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '不正な操作です');
});

test('clear failure3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 5.3, 'step' => 3.9]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '不正な操作です');
});

test('clear failure4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 'c', 'step' => 'g']));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '不正な操作です');
});

test('clear failure5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 15]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '不正な操作です');
});

test('clear failure6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 1, 'step' => 1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '記録できませんでした');
});

test('clear failure7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 15, 'step' => 2]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.index'));
  $response->assertSessionHas('status', '記録できませんでした');
});

test('clear failure8', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 1]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', 'チャレンジしていません');
});

test('clear failure9', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 10]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '記録できませんでした');
});

test('clear failure10', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 23, 'step' => 5]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 23]));
  $response->assertSessionHas('status', '既にクリアしています');
});

test('clear failure11', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 2]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '既にSTEP2をクリアしています');
});

test('clear failure12', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 5]));
  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('step.show', ['id' => 6]));
  $response->assertSessionHas('status', '前のSTEPをクリアしていません');
});

test('clear success1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 4]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'user_id' => 6,
    'step_id' => 6,
    'current_step' => 4
  ]);

  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 5]));
  $response->assertSessionHas('status', 'STEP4をクリアしました');
});

test('clear success2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 23, 'step' => 5]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'user_id' => 7,
    'step_id' => 23,
    'clear_flg' => 1
  ]);

  $response->assertRedirect(route('step.show', ['id' => 23]));
  $response->assertSessionHas('status', 'このSTEPをコンプリートしました');
});

test('clear success3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);
  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->put(route('small_step.clear', ['id' => 6, 'step' => 4]));
  //送信に成功
  $response->assertStatus(302);
  $this->assertDatabaseHas('challenges', [
    'user_id' => 7,
    'step_id' => 6,
    'current_step' => 4,
    'num_change_flg' => 0
  ]);

  $response->assertRedirect(route('step.show_small', ['id' => 6, 'step' => 5]));
  $response->assertSessionHas('status', 'STEP4をクリアしました');
});