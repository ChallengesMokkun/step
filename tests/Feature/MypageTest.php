<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Database\Seeders\TestCreateChallenges;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Log;

test('stepDetail screen cannot be rendered', function () {
  $response = $this->get(route('mypage'));
  $response->assertRedirect(route('login'));
});

test('stepDetail screen can be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mypage'));
  $response->assertStatus(200);
  $this->assertEquals(3, $response['data']['dbData']['mystepNum']);
  $this->assertEquals(3, $response['data']['dbData']['challengeNum']);

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('users.mypage');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mypage'));
  $response->assertStatus(200);
  $this->assertEquals(0, $response['data']['dbData']['mystepNum']);
  $this->assertEquals(2, $response['data']['dbData']['challengeNum']);
});

test('stepDetail screen can be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(24);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mypage'));
  $response->assertStatus(200);
  $this->assertEquals(0, $response['data']['dbData']['mystepNum']);
  $this->assertEquals(0, $response['data']['dbData']['challengeNum']);
});