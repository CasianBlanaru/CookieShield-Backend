import { NextResponse } from 'next/server';

// Diese Middleware wird bei jeder Anfrage ausgeführt
export function middleware(request) {
  const url = request.nextUrl.clone();
  const { pathname } = url;

  // Prüfen, ob es sich um eine API-Anfrage handelt
  if (pathname.startsWith('/api')) {
    // Umleiten der Anfrage an das Backend
    const backendUrl = new URL(
      pathname.replace('/api', '/api'),
      'https://cookieshield-backend-main-zdejjv.laravel.cloud'
    );

    // Kopieren von Suchparametern
    request.nextUrl.searchParams.forEach((value, key) => {
      backendUrl.searchParams.set(key, value);
    });

    return NextResponse.rewrite(backendUrl);
  }

  return NextResponse.next();
}

// Konfigurieren, für welche Pfade die Middleware ausgeführt werden soll
export const config = {
  matcher: ['/api/:path*'],
}; 