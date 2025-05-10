<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Middleware;

class EnsureFrontendRequestsAreStateful extends Middleware
{
    /**
     * Determine if the given request from the front-end when the request has no "Referer" header.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected static function frontendRequest($request)
    {
        // Falls die "Referer"-Kopfzeile fehlt (was der Grund für den 419-Fehler sein könnte),
        // behandeln wir die Anfrage trotzdem als Frontend-Anfrage
        if (!$request->headers->has('referer')) {
            return true;
        }

        $referer = $request->headers->get('referer');

        $frontendUrl = 'cookie-shield.vercel.app';
        
        $host = parse_url($referer, PHP_URL_HOST) ?: '';

        return Str::contains($host, $frontendUrl) || 
               Str::contains($host, 'localhost') || 
               Str::contains($host, '127.0.0.1');
    }
} 