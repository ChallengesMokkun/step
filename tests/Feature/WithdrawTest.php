<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\TestCreateUser;

test('withdrawal screen cannot be rendered', function () {
  $response = $this->get(route('withdraw'));
  $response->assertRedirect(route('login'));
  $this->assertGuest();
});

test('withdrawal screen can be rendered', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('withdraw'));

  $response->assertStatus(200);
  $this->assertEquals(2, count($response['memberFormData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('users.withdraw');
});

test('withdrawal success', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'password' => 'password',
  ];

  $response = $this->delete(route('withdraw'), $data);

  $this->assertDatabaseMissing('users', [
    'email' => $user->email,
  ]);

  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('login'));
  $this->assertGuest();
  $response->assertSessionHas('status', '退会しました');
});

test('empty', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [];

  $response = $this->delete(route('withdraw'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '必ず入力してください。'
  ]);
});

test('too many', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'password' => str_repeat('b', 256),
  ];

  $response = $this->delete(route('withdraw'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '255文字以下で入力してください。'
  ]);
});

test('type error1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'password' => str_repeat('b', 255),
  ];

  $response = $this->delete(route('withdraw'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '正しいパスワードを入力してください。'
  ]);
});

test('type error2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'password' => 'b',
  ];

  $response = $this->delete(route('withdraw'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '8文字以上で入力してください。'
  ]);
});

test('type error3', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'password' => 'b----<><>',
  ];

  $response = $this->delete(route('withdraw'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '使用可能な文字を入力してください'
  ]);
});
