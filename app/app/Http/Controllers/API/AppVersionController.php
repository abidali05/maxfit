<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;

class AppVersionController extends Controller
{
    public function show()
    {
        $appVersion = AppVersion::first();

        $data = [
            'androidVersion' => $appVersion?->android_version ?? '1.0.1',
            'playstoreLink' => $appVersion?->playstore_link ?? 'https://play.google.com/store/apps/details?id=com.sportsmax.maxfit',
            'iosVersion' => $appVersion?->ios_version ?? '1.0.3',
            'appStoreLink' => $appVersion?->app_store_link ?? '#',
        ];

        return response()->json([
            'status' => true,
            'message' => 'App version retrieved successfully',
            'data' => $data,
        ]);
    }
}

