<?php
namespace Tests\Feature;

use App\Models\Inquiry;

test('inquiry screen can be rendered', function () {
    $response = $this->get(route('inquiry'));

    $response->assertStatus(200);
    $this->assertEquals(6, count($response['data']));
    $this->assertEquals(4, count($response['commonData']));
    $response->assertViewIs('inquiry.new');
});



test('empty post', function () {

  $data = [];

  $response = $this->post(route('inquiry'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '必ず入力してください。',
    'email' => '必ず入力してください。',
    'purpose'=> '必ず入力してください。',
    'msg'=> '必ず入力してください。'
  ]);
});

test('too many characters1', function () {

  $data = [
    'name' => str_repeat('a', 21),
    'email' => str_repeat('b', 256),
    'purpose' => str_repeat('c', 256),
    'msg' => 'a'.str_repeat("\r\n", 30).'よろしくお願いします',
  ];

  $response = $this->post(route('inquiry'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'name' => '20文字以下で入力してください。',
    'email' => '255文字以下で入力してください。',
    'purpose'=> '255文字以下で入力してください。',
    'msg' => '改行を減らしてください。',
  ]);

});

test('too many characters2', function () {

  $data = [
    'name' => str_repeat('a', 20),
    'email' => str_repeat('b', 255),
    'purpose' => str_repeat('c', 255),
    'msg' => str_repeat('d', 501),
  ];

  $response = $this->post(route('inquiry'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
    'msg' => '500文字以下で入力してください。',
  ]);
});

test('type error1', function () {

  $data = [
    'name' => 'Test User',
    'email' => 'a@a.com',
    'purpose' => 'p',
    'msg' => str_repeat('d', 500),
  ];

  $response = $this->post(route('inquiry'), $data);

  $response->assertStatus(302);

  $response->assertInvalid([
    'email' => '不正な値です。',
  ]);
});

test('new inquiry can register', function () {

  $data = [
    'name' => 'Test User',
    'email' => 'challenges.mokkun11@gmail.com',
    'purpose' => 'このサイトについて',
    'msg' => '改善よろしくお願いします',
  ];

  $response = $this->post(route('inquiry'), $data);
  $response->assertStatus(302);

  $this->assertDatabaseHas('inquiries', [
    'email' => $data['email'],
  ]);

  $response->assertSessionHas('status', 'お問い合わせを送信しました');

});




