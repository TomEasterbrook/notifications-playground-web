<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard')
    ->middleware(['auth']);
Route::get('/serviceworker.js', function () {
    $assetPath = Vite::asset('resources/js/serviceworker.js');
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);
    $fileContent = file_get_contents($assetPath, false, $context);

    return response($fileContent, 200, [
        'Content-Type' => 'text/javascript',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('serviceworker');
