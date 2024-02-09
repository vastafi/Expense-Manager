<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Cerere primită', ['url' => $request->url(), 'method' => $request->method()]);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Logare după ce răspunsul a fost trimis clientului
        Log::info('Răspuns trimis', ['url' => $request->url(), 'status' => $response->status()]);
    }
}
