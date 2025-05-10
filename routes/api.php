<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\ConsentController;
use App\Http\Controllers\Api\CookieSettingController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Direkte Routen für lokale Entwicklung
Route::get('/config', [ConfigController::class, 'getConfig']);
Route::post('/consent', [ConsentController::class, 'storeConsent']);
Route::get('/location', function() {
    return response()->json(['country' => 'DE', 'region' => 'EU']);
});

// Cookie Consent API Routen
Route::prefix('prod')->group(function () {
    // Konfiguration abrufen
    Route::get('/config.json', [ConfigController::class, 'getConfig']);

    // Konfiguration speichern
    Route::post('/config', [ConfigController::class, 'storeConfig']);

    // Consent Routen
    Route::prefix('consent')->group(function () {
        // Consent speichern
        Route::post('/', [ConsentController::class, 'storeConsent']);

        // Consent für einen Besucher abrufen
        Route::get('/{visitorId}', [ConsentController::class, 'getConsentByVisitor']);

        // Consents eines Besuchers löschen
        Route::delete('/{visitorId}', [ConsentController::class, 'deleteVisitorConsents']);
    });
});

// Admin-Routen (optional mit Authentifizierung absichern)
Route::prefix('admin')->group(function () {
    // Config-Verwaltung
    Route::get('/configs', [ConfigController::class, 'index']);
    Route::get('/configs/{id}', [ConfigController::class, 'show']);
    Route::put('/configs/{id}', [ConfigController::class, 'update']);
    Route::delete('/configs/{id}', [ConfigController::class, 'destroy']);

    // Consent-Verwaltung
    Route::get('/consents', [ConsentController::class, 'index']);
    Route::get('/consents/{id}', [ConsentController::class, 'show']);
    Route::get('/consents/api-key/{apiKey}', [ConsentController::class, 'getConsentsByApiKey']);
});

// Cookie Dashboard API Routes - ohne Middleware für öffentlichen Zugriff
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// CSRF-Token Route
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['token' => csrf_token()]);
});

// Für Routen, die sowohl Authentifizierung als auch Session-Zustand benötigen
Route::middleware('stateful.api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cookie-settings', [CookieSettingController::class, 'show']);
        Route::post('/cookie-settings', [CookieSettingController::class, 'update']);
    });
});

// Test-Route
Route::get('/test', function () {
    return response()->json(['message' => 'API funktioniert!']);
});

// Proxy-Route für Frontend-Anfragen
Route::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], 'proxy/{path}', function (Request $request, $path) {
    // Bei OPTIONS-Anfragen direkt mit 200 OK antworten
    if ($request->isMethod('OPTIONS')) {
        return response()->json(['status' => 'ok'], 200);
    }
    
    $method = $request->method();
    $targetPath = $path;
    
    // Protokoll der proxierten Anfrage
    \Log::info("Proxy request: $method $targetPath", [
        'headers' => $request->headers->all(),
        'params' => $request->all()
    ]);
    
    // Die Anfrage an den Controller weiterleiten
    if (strtolower($targetPath) === 'register' && $method === 'POST') {
        return app()->call('\App\Http\Controllers\Api\AuthController@register', ['request' => $request]);
    } elseif (strtolower($targetPath) === 'login' && $method === 'POST') {
        return app()->call('\App\Http\Controllers\Api\AuthController@login', ['request' => $request]);
    }
    
    // Für andere Routen
    return response()->json(['error' => 'Route not found'], 404);
})->where('path', '.*');
