<?php
namespace Tests\Feature;

use App\Models\Inquiry;

test('terms-of-service screen can be rendered', function () {
    $response = $this->get(route('tos'));

    $response->assertStatus(200);
    $response->assertViewIs('informations.tos');
});

test('privacy policy screen can be rendered', function () {
  $response = $this->get(route('privacy'));

  $response->assertStatus(200);
  $response->assertViewIs('informations.privacy');
});