<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\TestCreateUser;

test('passedit screen cannot be rendered', function () {
  $response = $this->get(route('password.edit'));
  $response->assertRedirect(route('login'));
  $this->assertGuest();
});

test('passedit screen can be rendered', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('password.edit'));

  $response->assertStatus(200);
  $this->assertEquals(2, count($response['memberFormData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('users.passedit');
});

test('passedit success', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'current_password' => 'password',
    'password' => '12345678',
    'password_confirmation' => '12345678'
  ];

  $response = $this->put(route('password.edit'), $data);


  //送信に成功
  $response->assertStatus(302);

  $user = User::select('password')->where('id', 6)->first()->makeVisible(['password']);
  $this->assertEquals(true, password_verify('12345678', $user->password));
  $response->assertRedirect(route('mypage'));
  $response->assertSessionHas('status', 'パスワードを変更しました');
});

test('empty', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [];

  $response = $this->put(route('password.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'current_password' => '必ず入力してください。',
    'password'=> '必ず入力してください。'
  ]);
});

test('too many', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'current_password' => str_repeat('a', 256),
    'password' => str_repeat('b', 256),
    'password_confirmation' => str_repeat('b', 256)
  ];

  $response = $this->put(route('password.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'current_password' => '255文字以下で入力してください。',
    'password'=> '255文字以下で入力してください。'
  ]);
});

test('type error1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'current_password' => str_repeat('a', 255),
    'password' => str_repeat('b', 255),
    'password_confirmation' => str_repeat('c', 255)
  ];

  $response = $this->put(route('password.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'current_password' => '正しいパスワードを入力してください。',
    'password'=> '正しく二度入力してください'
  ]);
});

test('type error2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'current_password' => 'password',
    'password' => 'b',
    'password_confirmation' => 'c'
  ];

  $response = $this->put(route('password.edit'), $data);

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
    'current_password' => 'password',
    'password' => 'b----<><>',
    'password_confirmation' => 'b----<><>'
  ];

  $response = $this->put(route('password.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '使用可能な文字を入力してください'
  ]);
});

test('type error4', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'current_password' => 'password',
    'password' => 'password',
    'password_confirmation' => 'password'
  ];

  $response = $this->put(route('password.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'password'=> '今のパスワードと違うものを入力してください。'
  ]);
});