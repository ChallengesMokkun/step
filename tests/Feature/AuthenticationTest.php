<?php
namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\TestCreateUser;
use Illuminate\Support\Facades\Log;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);

    $this->assertEquals(2, count($response['memberFormData']));
    $this->assertEquals(4, count($response['commonData']));
    $response->assertViewIs('users.login');
});

test('users can authenticate using the login screen', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $response = $this->post(route('login'), [
      'email' => $user->email,
      'password' => 'password',
  ]);

  // リダイレクトでページ遷移してくるのでstatusは302
  $response->assertStatus(302);
  // リダイレクトで帰ってきた時のパス
  $response->assertRedirect(RouteServiceProvider::HOME);
  // このユーザーがログイン認証されているか
  $this->assertAuthenticated();
  $response->assertSessionHas('status', 'ログインしました');
});

test('empty', function () {
  $data = [];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();

  $response->assertInvalid([
    'email' => '必ず入力してください。',
    'password'=> '必ず入力してください。'
  ]);
});

test('too many', function () {
  $data = [
    'email' => str_repeat('a', 256),
    'password'  => str_repeat('a', 256)
  ];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();

  $response->assertInvalid([
    'email' => '255文字以下で入力してください。',
    'password'=> '255文字以下で入力してください。'
  ]);
});

test('type error1', function () {
  $data = [
    'email' => 'a@a.com',
    'password'  => 'b'
  ];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '8文字以上で入力してください。',
  ]);
});

test('type error2', function () {

  $data = [
    'email' => 'a@a',
    'password'  => 'b----<><>'
  ];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '使用可能な文字を入力してください',
  ]);
});

test('wrong1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $data = [
    'email' => 'a@gmail.com',
    'password'  => 'bbbbbbbbb'
  ];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();

  $response->assertInvalid([
    'auth' => 'いずれかが違うか、登録されていません。'
  ]);
});

test('wrong2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);

  $data = [
    'email' => $user->email,
    'password'  => 'bbbbbbbbb'
  ];

  $response = $this->post(route('login'), $data);
  $response->assertStatus(302);
  // 失敗しているので認証されていない事
  $this->assertGuest();
  $response->assertInvalid([
    'auth' => 'いずれかが違うか、登録されていません。'
  ]);
});

test('Logout test', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->post(route('logout'));

  // リダイレクトでページ遷移してくるのでstatusは302
  $response->assertStatus(302);
  $response->assertRedirect(route('login'));
  $this->assertGuest();
  $response->assertSessionHas('status', 'ログアウトしました');
});

