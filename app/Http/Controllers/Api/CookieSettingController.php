<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CookieSetting;
use Illuminate\Http\Request;

class CookieSettingController extends Controller
{
    public function show() {
        $settings = CookieSetting::first() ?? new CookieSetting(['settings' => []]);
        return response()->json($settings->settings);
    }
    
    public function update(Request $request) {
        $validated = $request->validate([
            'bannerMessage' => 'required|string',
            'acceptAllLabel' => 'required|string',
            'denyAllLabel' => 'required|string',
            'settingsLabel' => 'required|string',
            'saveLabel' => 'required|string',
            'consentLifetime' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'in:necessary,performance,functional,advertising',
            'bannerPosition' => 'required|in:top,bottom',
            'bannerBgColor' => 'required|string',
            'bannerTextColor' => 'required|string',
            'buttonBgColor' => 'required|string',
            'buttonTextColor' => 'required|string',
            'forcedLang' => 'nullable|string|max:10',
            'translations' => 'nullable|array',
            'translations.*.bannerMessage' => 'nullable|string',
            'translations.*.acceptAllLabel' => 'nullable|string',
            'translations.*.denyAllLabel' => 'nullable|string',
            'translations.*.settingsLabel' => 'nullable|string',
            'translations.*.saveLabel' => 'nullable|string',
            'fontFamily' => 'nullable|string',
            'buttonBorderRadius' => 'nullable|string',
            'bannerAnimation' => 'nullable|string|in:none,fade,slide,bounce',
        ]);
        
        $settings = CookieSetting::first() ?? new CookieSetting();
        $settings->settings = $validated;
        $settings->save();
        
        return response()->json(['message' => 'Settings saved', 'settings' => $settings->settings]);
    }
}
