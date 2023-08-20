<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Database\Seeders\TestCreateUser;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get(route('password.forgot'));

    $response->assertStatus(200);
    $this->assertEquals(2, count($response['memberFormData']));
    $this->assertEquals(4, count($response['commonData']));
    $response->assertViewIs('users.forgot-pass');
});

test('reset password link can be requested', function () {
    Notification::fake();

    $this->seed(TestCreateUser::class);
  
    $user = User::find(6);


    $response = $this->post(route('password.forgot'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
    $response->assertSessionHas('status', 'リセットリンクを送信しました。');
});

test('reset password link cannot be requested1', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => null]);

  $response->assertInvalid([
    'email' => '必ず入力してください。',
  ]);
});

test('reset password link cannot be requested2', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => str_repeat('a', 256)]);

  $response->assertInvalid([
    'email' => '255文字以下で入力してください。',
  ]);
});

test('reset password link cannot be requested3', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => str_repeat('a', 255)]);

  $response->assertInvalid([
    'email' => '不正な値です。',
  ]);
});

test('reset password link cannot be requested4', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => 'a@a']);

  $response->assertInvalid([
    'email' => '不正な値です。',
  ]);
});

test('reset password link cannot be requested5', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => 'a@a.com']);

  $response->assertInvalid([
    'email' => '不正な値です。',
  ]);
});

test('reset password link cannot be requested6', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);


  $response = $this->post(route('password.forgot'), ['email' => 'aaa@gmail.com']);

  $response->assertInvalid([
    'auth' => '登録情報が見つかりませんでした。',
  ]);
});


test('reset password screen can be rendered', function () {
    Notification::fake();

    $this->seed(TestCreateUser::class);
  
    $user = User::find(6);

    $this->post(route('password.forgot'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get(route('password.reset', ['token' => $notification->token]));

        $response->assertStatus(200);
        $this->assertEquals(2, count($response['memberFormData']));
        $this->assertEquals(4, count($response['commonData']));
        $response->assertViewIs('users.reset-pass');

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $this->seed(TestCreateUser::class);
  
    $user = User::find(6);

    $this->post(route('password.forgot'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->put(route('password.store'), [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', 'リセットしました。');

        return true;
    });
});

test('password cannot be reset with valid token1', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
      $response = $this->put(route('password.store'), []);

      $response->assertInvalid([
        'email' => '必ず入力してください。',
        'token' => '必ず入力してください。',
        'password' => '必ず入力してください。',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token2', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => str_repeat('a', 256),
      'password' => str_repeat('a', 256),
      'password_confirmation' => str_repeat('b', 256),
    ]);

      $response->assertInvalid([
        'email' => '255文字以下で入力してください。',
        'password' => '255文字以下で入力してください。',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token3', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => str_repeat('a', 255),
      'password' => str_repeat('a', 255),
      'password_confirmation' => str_repeat('b', 255),
    ]);

      $response->assertInvalid([
        'email' => '不正な値です。',
        'password' => '正しく二度入力してください',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token4', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => 'a@a',
      'password' => 'a',
      'password_confirmation' => 'a',
    ]);

      $response->assertInvalid([
        'email' => '不正な値です。',
        'password' => '8文字以上で入力してください。',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token5', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => 'a@a.com',
      'password' => 'b----<><>',
      'password_confirmation' => 'b----<><>',
    ]);

      $response->assertInvalid([
        'email' => '不正な値です。',
        'password' => '使用可能な文字を入力してください',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token6', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => $user->email,
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

      $response->assertInvalid([
        'password' => '違うパスワードを設定してください',
      ]);

      return true;
  });
});

test('password cannot be reset with valid token7', function () {
  Notification::fake();

  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $this->post(route('password.forgot'), ['email' => $user->email]);

  Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
    $response = $this->put(route('password.store'), [
      'token' => $notification->token,
      'email' => 'xxx@gmail.com',
      'password' => '12345678',
      'password_confirmation' => '12345678',
    ]);

      $response->assertInvalid([
        'auth' => '有効期限を過ぎているか、無効なリンクです。',
      ]);

      return true;
  });
});