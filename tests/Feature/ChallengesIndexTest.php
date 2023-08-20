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
  $response = $this->get(route('challenges'));
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

  $response = $this->get(route('challenges'));
  $response->assertStatus(200);

  $this->assertEquals(3, $response['data']['dbData']->count());
  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.challenges');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges'));
  $response->assertStatus(200);

  $this->assertEquals(2, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(24);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges'));
  $response->assertStatus(200);
  
  $this->assertEquals(0, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges', ['page' => 'a']));
  $response->assertRedirect(route('challenges'));
});

test('stepDetail screen can be rendered5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges', ['page' => -3]));
  $response->assertRedirect(route('challenges'));
});

test('stepDetail screen can be rendered6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges', ['page' => 6.4]));
  $response->assertRedirect(route('challenges'));
});

test('stepDetail screen can be rendered7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);
  $this->seed(TestCreateChallenges::class);

  $user = User::find(7);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('challenges', ['page' => 100]));
  $response->assertRedirect(route('challenges'));
});