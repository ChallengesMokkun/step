<?php
namespace Tests\Unit;

use App\Function\HelpFunc;

test('invalidNumberParam1', function () {
  $this->assertTrue(HelpFunc::invalidNumberParam('a'));
});

test('invalidNumberParam2', function () {
  $this->assertTrue(HelpFunc::invalidNumberParam(-9));
});

test('invalidNumberParam3', function () {
  $this->assertTrue(HelpFunc::invalidNumberParam(6.5));
});

test('validNumberParam', function () {
  $this->assertFalse(HelpFunc::invalidNumberParam(124));
});


test('genarateGetParam1', function () {
  $params = HelpFunc::genarateGetParam(null);

  $mode = array_count_values($params);

  $this->assertContains('?page=', $params);
  $this->assertEquals(2, $mode['']);
});

test('genarateGetParam2', function () {
  $params = HelpFunc::genarateGetParam(null, 'mypage');

  $this->assertContains('?page=', $params);
  $this->assertContains('?from=mypage', $params);
  $this->assertContains('?from=mypage', $params);
});


test('genarateGetParam3', function () {
  $params = HelpFunc::genarateGetParam(null, 'profile');

  $this->assertContains('?page=', $params);
  $this->assertContains('?from=profile', $params);
  $this->assertContains('', $params);
});

test('genarateGetParam4', function () {
  $params = HelpFunc::genarateGetParam(['user_id' => 1, 'category_id' => 3, 'keyword' => 'こと']);

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと', $params);
});

test('genarateGetParam5', function () {
  $params = HelpFunc::genarateGetParam(['page' => 5, 'user_id' => 1, 'category_id' => 3, 'keyword' => 'こと']);

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=5', $params);
});

test('genarateGetParam6', function () {
  $params = HelpFunc::genarateGetParam(['user_id' => 1, 'category_id' => 3, 'keyword' => 'こと'], 'challenge');

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&from=challenge', $params);
});

test('genarateGetParam7', function () {
  $params = HelpFunc::genarateGetParam(['user_id' => 1, 'category_id' => 3, 'keyword' => 'こと'], 'mystep');

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&from=mystep', $params);
});

test('genarateGetParam8', function () {
  $params = HelpFunc::genarateGetParam(['page' => 5, 'user_id' => 1, 'category_id' => 3, 'keyword' => 'こと'], 'challenge');

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&from=challenge&page=5', $params);
});

test('genarateGetParam9', function () {
  $params = HelpFunc::genarateGetParam(['page' => 5, 'user_id' => 1, 'category_id' => 3, 'keyword' => 'こと'], 'mystep');

  // $strings = '?user_id=1&category_id=3&keyword=こと&';

  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&page=5', $params);
  $this->assertContains('?user_id=1&category_id=3&keyword=こと&from=mystep&page=5', $params);
});