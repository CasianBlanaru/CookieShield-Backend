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
        // Bei API-Routen als stateful behandeln
        if (Str::startsWith($request->path(), 'api/')) {
            return true;
        }

        // Falls die "Referer"-Kopfzeile fehlt (was der Grund für den 419-Fehler sein könnte),
        // behandeln wir die Anfrage trotzdem als Frontend-Anfrage
        if (!$request->headers->has('referer')) {
            return true;
        }

        $referer = $request->headers->get('referer');

        // Überprüfen auf bekannte Frontend-Domains
        $frontendUrls = [
            'cookie-shield.vercel.app',
            'cookie-shield-7lauglqq1-casianus-projects-f4ba8bd9.vercel.app'
        ];
        
        $host = parse_url($referer, PHP_URL_HOST) ?: '';

        // Entweder eine bekannte Domain oder ein Muster einer Vercel-Preview-Domain
        return in_array($host, $frontendUrls) || 
               Str::contains($host, 'localhost') || 
               Str::contains($host, '127.0.0.1') ||
               (Str::contains($host, 'cookie-shield') && Str::contains($host, 'vercel.app'));
    }
} 