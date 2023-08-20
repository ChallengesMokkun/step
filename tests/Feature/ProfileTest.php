<?php

use App\Models\User;
use Database\Seeders\TestCreateUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('profedit screen cannot be rendered', function () {
  $response = $this->get(route('profile.edit'));
  $response->assertRedirect(route('login'));
  $this->assertGuest();
});

test('profedit screen can be rendered', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('profile.edit'));

  $response->assertStatus(200);
  $this->assertEquals(2, count($response['memberFormData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('users.profedit');
});

test('profedit success1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  Storage::fake('local');
  $file = UploadedFile::fake()->image('avatar.jpg')->size(10240);

  $data = [
    'name' => 'ccc',
    'email' => 'challenges.mokkun6@gmail.com',
    'password' => 'password',
    'pic' => $file,
    'introduction' => 'よろしく！！'
  ];

  $response = $this->patch(route('profile.edit'), $data);

  $user = User::find(6);
  $this->assertEquals(true, !empty($user->pic));
  $this->assertEquals('challenges.mokkun6@gmail.com', $user->email);

  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $response->assertSessionHas('status', 'プロフィールを更新しました');
});

test('profedit success2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  Storage::fake('local');
  $file = UploadedFile::fake()->image('avatar.gif')->size(10240);

  $data = [
    'name' => 'ddd',
    'email' => $user->email,
    'password' => 'password',
    'pic' => $file,
    'introduction' => 'よろしく！！'
  ];

  $response = $this->patch(route('profile.edit'), $data);

  $user = User::find(6);
  $this->assertEquals(true, !empty($user->pic));

  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $response->assertSessionHas('status', 'プロフィールを更新しました');
});

test('profedit success3', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [
    'name' => 'eee',
    'email' => $user->email,
    'password' => 'password',
    'pic' => null,
    'introduction' => 'よろしく！！'
  ];

  $response = $this->patch(route('profile.edit'), $data);

  $user = User::find(6);
  $this->assertEquals('eee', $user->name);
  $this->assertEquals('よろしく！！', $user->introduction);

  //送信に成功
  $response->assertStatus(302);
  $response->assertRedirect(route('mypage'));
  $response->assertSessionHas('status', 'プロフィールを更新しました');
});

test('empty', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $data = [];

  $response = $this->patch(route('profile.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '必ず入力してください。',
    'email' => '必ず入力してください。',
    'password'=> '必ず入力してください。'
  ]);
});


test('too many1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  Storage::fake('local');

  $data = [
    'name' => str_repeat('a', 21),
    'email' => str_repeat('b', 256),
    'password' => str_repeat('c', 256),
    'introduction' => 'a'.str_repeat("\r\n", 22).'よろしくお願いします',
    'pic' => UploadedFile::fake()->image('avatar.jpg')->size(10241),
  ];

  $response = $this->patch(route('profile.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '20文字以下で入力してください。',
    'email' => '255文字以下で入力してください。',
    'password'=> '255文字以下で入力してください。',
    'introduction' => '改行を減らしてください。',
    'pic' => '規定容量以下の画像を選択してください。'
  ]);
});

test('too many2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  Storage::fake('local');

  $data = [
    'name' => str_repeat('a', 20),
    'email' => str_repeat('b', 255),
    'password' => str_repeat('c', 255),
    'introduction' => str_repeat('d', 301),
    'pic' => UploadedFile::fake()->image('avatar.gif')->size(10241),
  ];

  $response = $this->patch(route('profile.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '正しいパスワードを入力してください。',
    'introduction' => '300文字以下で入力してください。',
    'pic' => '規定容量以下の画像を選択してください。'
  ]);
});

test('type error1', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  Storage::fake('local');

  $data = [
    'name' => 'Test User',
    'email' => 'a@a.com',
    'password' => 'p',
    'introduction' => str_repeat('d', 300),
    'pic' => UploadedFile::fake()->create('document.pdf', 4096, 'application/pdf')
  ];

  $response = $this->patch(route('profile.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
    'password'=> '8文字以上で入力してください。',
    'pic' => '非対応のファイルです。'
  ]);
});

test('type error2', function () {
  $this->seed(TestCreateUser::class);

  $user = User::find(6);
  $this->actingAs($user);

  $user2 = User::create([
    'name' => 'ccc',
    'email' => 'ccc@gmail.com',
    'password' => bcrypt('password'),
    'introduction' => 'よろしくお願いします！'
  ]);

  $this->assertAuthenticated();

  Storage::fake('local');

  $data = [
    'name' => 'Test User',
    'email' => 'ccc@gmail.com',
    'password' => '<><>----p',
    'password_confirmation' => '<><>----p',
    'introduction' => str_repeat('d', 300),
    'pic' => UploadedFile::fake()->create('photo.tiff', 4096, 'image/tiff')
  ];

  $response = $this->patch(route('profile.edit'), $data);

  //送信に成功
  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '無効なメールアドレスです。',
    'password'=> '使用可能な文字を入力してください',
    'pic' => '非対応のファイルです。'
  ]);
});