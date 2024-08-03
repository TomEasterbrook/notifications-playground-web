<?php

use App\Http\Controllers\NotificationDevicesController;
use App\Http\Controllers\ServiceWorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/serviceworker.js', [ServiceWorkerController::class, 'index'])
    ->name('serviceworker');

Route::post('/notification-devices/register', [NotificationDevicesController::class, 'registerAndStore'])
    ->middleware(['auth']);

Route::delete('/notification-devices/remove', [NotificationDevicesController::class, 'removeDevice']);
