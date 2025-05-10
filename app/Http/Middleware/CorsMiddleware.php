<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            $response = new Response('', 200);
        } else {
            // Handle normal requests
            $response = $next($request);
        }
        
        // Set CORS headers
        $allowedOrigins = [
            'http://localhost:3000', 
            'http://localhost:3001', 
            'http://localhost:8080', 
            'https://cookie-shield.vercel.app',
            'https://cookie-shield-7lauglqq1-casianus-projects-f4ba8bd9.vercel.app',
            'https://cookie-shield-3lzvtex0u-casianus-projects-f4ba8bd9.vercel.app'
        ];
        $origin = $request->header('Origin');
        
        // Überprüfen, ob die Herkunft in der Liste der erlaubten Quellen ist
        // oder dem Muster einer Vercel-Preview-Domain entspricht
        $isAllowed = in_array($origin, $allowedOrigins) || 
                     (strpos($origin, 'https://cookie-shield') === 0 && 
                      strpos($origin, '.vercel.app') !== false) ||
                     (strpos($origin, 'http://localhost') === 0);
        
        if ($isAllowed) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        } else {
            // Wenn der Ursprung nicht erlaubt ist, setzen wir trotzdem einen Wert, 
            // um ein "Missing Allow Origin Header"-Problem zu vermeiden
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Credentials', 'false');
        }
        
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'X-CSRF-TOKEN, Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN, Accept, Origin');
        $response->headers->set('Access-Control-Max-Age', '86400'); // 24 hours
        
        return $response;
    }
}
