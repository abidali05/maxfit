<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class AppVersionController extends Controller
{
    public function edit()
    {
        $appVersion = AppVersion::first();

        if (!$appVersion) {
            $appVersion = new AppVersion([
                'android_version' => '1.0.1',
                'playstore_link' => 'https://play.google.com/store/apps/details?id=com.sportsmax.maxfit',
                'ios_version' => '1.0.3',
                'app_store_link' => '#',
            ]);
        }

        return view('app-version.edit', compact('appVersion'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'android_version' => 'required|string|max:50',
            'playstore_link' => 'required|string|max:255',
            'ios_version' => 'required|string|max:50',
            'app_store_link' => 'required|string|max:255',
        ]);

        AppVersion::updateOrCreate(
            ['id' => 1],
            $validated
        );

        Toastr::success('App version settings updated successfully', 'Success');

        return redirect()->route('app-version.edit');
    }
}
