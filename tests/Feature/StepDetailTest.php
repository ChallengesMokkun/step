<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateStep;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('stepDetail screen cannot be rendered1', function () {
  $response = $this->get(route('step.show', ['id' => 'a']));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered2', function () {
  $response = $this->get(route('step.show', ['id' => -1]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered3', function () {
  $response = $this->get(route('step.show', ['id' => 3.5]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $response = $this->get(route('step.show', ['id' => 1]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen cannot be rendered5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $response = $this->get(route('step.show', ['id' => 15]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $response = $this->get(route('step.show', ['id' => 6]));
  $response->assertStatus(200);

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.show');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateStep::class);

  $user = User::find(6);
  $this->actingAs($user);

  $this->assertAuthenticated();

  $response = $this->get(route('step.show', ['id' => 15]));
  $response->assertStatus(200);
});