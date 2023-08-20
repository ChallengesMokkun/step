<?php

use App\Models\User;
use Carbon\Carbon;
use App\Models\Step;
use Database\Seeders\TestCreateCategories;
use Database\Seeders\TestCreateUser;
use Database\Seeders\TestCreateManySteps;
use Illuminate\Foundation\Testing\RefreshDatabase;


test('stepDetail screen can be rendered1', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index'));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());

  $this->assertEquals(13, count($response['stepData']['categories']));
  $this->assertEquals(6, count($response['stepData']));
  $this->assertEquals(4, count($response['commonData']));
  $response->assertViewIs('steps.index');
});

test('stepDetail screen can be rendered2', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 'a']));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered3', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => -1]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered4', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 3.6]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered5', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 3]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered6', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(8, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered7', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['category_id' => 1]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered8', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['category_id' => 33]));
  $response->assertRedirect(route('step.index'));
});

test('stepDetail screen can be rendered9', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['keyword' => 'こと']));
  $response->assertStatus(200);
  $this->assertEquals(8, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered10', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['keyword' => 'こと しょう']));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered11', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['category_id' => 1, 'keyword' => 'こと しょう']));
  $response->assertStatus(200);
  $this->assertEquals(8, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered12', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['category_id' => 1, 'keyword' => 'こと しょう', 'user_id' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(8, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered13', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 3, 'user_id' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered14', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['category_id' => 1, 'user_id' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered15', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['keyword' => 'こと しょう', 'user_id' => 6]));
  $response->assertStatus(200);
  $this->assertEquals(12, $response['data']['dbData']->count());
});

test('stepDetail screen can be rendered16', function () {
  $this->seed(TestCreateUser::class);
  $this->seed(TestCreateCategories::class);
  $this->seed(TestCreateManySteps::class);

  $response = $this->get(route('step.index', ['page' => 100]));
  $response->assertRedirect(route('step.index'));
});
