<?php
namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get(route('user.register'));

    $response->assertStatus(200);
    $this->assertEquals(2, count($response['memberFormData']));
    $this->assertEquals(4, count($response['commonData']));
    $response->assertViewIs('users.register');
});

test('new users can register1', function () {
  Storage::fake('local');
  $file = UploadedFile::fake()->image('avatar.jpg')->size(10240);

  $data = [
    'name' => 'Test User',
    'email' => 'challenges.mokkun11@gmail.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'introduction' => 'よろしくお願いします',
    'pic' => $file
  ];

  $response = $this->post(route('user.register'), $data);

  $user = User::where('email', $data['email'])->first();
  $this->assertEquals(true, !empty($user));
  $this->assertEquals(true, !empty($user->pic));

  // リダイレクトでページ遷移してくるのでstatusは302
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $this->assertAuthenticated();
  $response->assertSessionHas('status', 'ユーザー登録しました');
});

test('new users can register2', function () {
  Storage::fake('local');
  $file = UploadedFile::fake()->image('avatar.gif')->size(10240);

  $data = [
    'name' => 'Test User2',
    'email' => 'challenges.mokkun11@gmail.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'introduction' => 'よろしくお願いします',
    'pic' => $file
  ];

  $response = $this->post(route('user.register'), $data);

  $user = User::where('email', $data['email'])->first();
  $this->assertEquals(true, !empty($user));
  $this->assertEquals(true, !empty($user->pic));


  // リダイレクトでページ遷移してくるのでstatusは302
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $this->assertAuthenticated();
  $response->assertSessionHas('status', 'ユーザー登録しました');
});

test('new users can register3', function () {

  $data = [
    'name' => 'Test User3',
    'email' => 'challenges.mokkun11@gmail.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'introduction' => 'よろしくお願いします',
    'pic' => null
  ];

  $response = $this->post(route('user.register'), $data);

  $this->assertDatabaseHas('users', [
    'email' => $data['email'],
  ]);


  // リダイレクトでページ遷移してくるのでstatusは302
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $this->assertAuthenticated();
  $response->assertSessionHas('status', 'ユーザー登録しました');
});

test('empty post', function () {

  $data = [];

  $response = $this->post(route('user.register'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '必ず入力してください。',
    'email' => '必ず入力してください。',
    'password'=> '必ず入力してください。'
  ]);

  // 失敗しているので認証されていない事
  $this->assertGuest();
});

test('too many1', function () {

  Storage::fake('local');

  $data = [
    'name' => str_repeat('a', 21),
    'email' => str_repeat('b', 256),
    'password' => str_repeat('c', 256),
    'password_confirmation' => str_repeat('c', 256),
    'introduction' => 'a'.str_repeat("\r\n", 22).'よろしくお願いします',
    'pic' => UploadedFile::fake()->image('avatar.jpg')->size(10343),
  ];

  $response = $this->post(route('user.register'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '20文字以下で入力してください。',
    'email' => '255文字以下で入力してください。',
    'password'=> '255文字以下で入力してください。',
    'introduction' => '改行を減らしてください。',
    'pic' => '規定容量以下の画像を選択してください。'
  ]);

  // 失敗しているので認証されていない事
  $this->assertGuest();
});

test('too many2', function () {
  Storage::fake('local');

  $data = [
    'name' => str_repeat('a', 20),
    'email' => str_repeat('b', 255),
    'password' => str_repeat('c', 255),
    'password_confirmation' => str_repeat('e', 255),
    'introduction' => str_repeat('d', 301),
    'pic' => UploadedFile::fake()->image('avatar.jpg')->size(10240),
  ];

  $response = $this->post(route('user.register'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '正しく二度入力してください',
    'introduction' => '300文字以下で入力してください。',
  ]);

  // 失敗しているので認証されていない事
  $this->assertGuest();
});

test('type error1', function () {
  Storage::fake('local');

  $data = [
    'name' => 'Test User',
    'email' => 'a@a.com',
    'password' => 'p',
    'password_confirmation' => 'p',
    'introduction' => str_repeat('d', 300),
    'pic' => UploadedFile::fake()->create('document.pdf', 4096, 'application/pdf')
  ];

  $response = $this->post(route('user.register'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '8文字以上で入力してください。',
    'pic' => '非対応のファイルです。'
  ]);

  // 失敗しているので認証されていない事
  $this->assertGuest();
});

test('type error2', function () {
  Storage::fake('local');

  User::create([
    'name' => 'Test User',
    'email' => 'ccc@gmail.com',
    'password' => 'password',
    'introduction' => str_repeat('d', 300),
    'pic' => null
  ]);

  $data = [
    'name' => 'Test User',
    'email' => 'ccc@gmail.com',
    'password' => '<><>----p',
    'password_confirmation' => '<><>----p',
    'introduction' => str_repeat('d', 300),
    'pic' => UploadedFile::fake()->create('photo.tiff', 4096, 'image/tiff')
  ];

  $response = $this->post(route('user.register'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '無効なメールアドレスです。',
    'password'=> '使用可能な文字を入力してください',
    'pic' => '非対応のファイルです。'
  ]);

  // 失敗しているので認証されていない事
  $this->assertGuest();
});



