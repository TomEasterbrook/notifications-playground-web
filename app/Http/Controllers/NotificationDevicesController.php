<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationDevicesResource;
use App\Models\NotificationDevices;
use Illuminate\Http\Request;

class NotificationDevicesController extends Controller
{
    public function registerAndStore(Request $request)
    {
        $proposedDeviceId = \Str::uuid();

        NotificationDevices::create([
            'device_id' => $proposedDeviceId,
            'user_id' => $request->user()->id,
        ]);
        return response()->json([
            'device_id' => $proposedDeviceId,
        ]);
    }
}
