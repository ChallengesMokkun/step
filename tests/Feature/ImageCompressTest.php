<?php
namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Function\HelpFunc;

test('compressImage', function () {
  Storage::fake('local');
  $fileName = 'avatar.jpg';
  $file = UploadedFile::fake()->image($fileName)->size(10240);

  HelpFunc::compressImage($file, $fileName);

  Storage::disk('local')->assertExists('public/uploads/'.$fileName);
});
