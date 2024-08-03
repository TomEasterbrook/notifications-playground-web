<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Vite;

class ServiceWorkerController extends Controller
{
    public function index()
    {
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
    }
}
