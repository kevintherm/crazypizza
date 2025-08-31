<?php

use Helper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('can move an uploaded file', function() {
    /** @var Tests\TestCase $this */

    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    Helper::uploadFile($file, 'test-folder', 'public');

    Storage::disk('public')->assertExists('test-folder/' . $file->hashName());
});

test('can delete a file', function() {
    /** @var Tests\TestCase $this */

    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    Helper::uploadFile($file, 'test-folder', 'public');

    Storage::disk('public')->assertExists('test-folder/' . $file->hashName());

    Helper::deleteFile('test-folder/' . $file->hashName(), 'public');

    Storage::disk('public')->assertMissing('test-folder/' . $file->hashName());

});
