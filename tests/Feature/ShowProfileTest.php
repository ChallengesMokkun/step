<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateManySteps;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('stepDetail screen cannot be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => 'a']));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => -1]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => 4.6]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => 47]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(6, $response['data']['dbData']['num']);

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.show_profile');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('profile.show', ['id' => 7]));
  $response->assertStatus(200);
  $this->assertEquals(0, $response['data']['dbData']['num']);
});

