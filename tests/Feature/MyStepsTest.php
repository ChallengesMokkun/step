<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateManySteps;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('stepDetail screen cannot be rendered', function () {
  $response = $this->get(route('mystep'));
  $response->assertRedirect(route('login'));
});

test('stepDetail screen can be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep'));
  $response->assertStatus(200);

  $this->assertEquals(12, $response['data']['dbData']->count());

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.mystep');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => 3]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(8, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => 'あ']));
  $response->assertRedirect(route('mystep'));
});

test('stepDetail screen can be rendered5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => -1]));
  $response->assertRedirect(route('mystep'));
});

test('stepDetail screen can be rendered6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => 5.8]));
  $response->assertRedirect(route('mystep'));
});

test('stepDetail screen can be rendered7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('mystep', ['page' => 100]));
  $response->assertRedirect(route('mystep'));
});