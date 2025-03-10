<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCsrfForApi
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            \Log::info('CSRF check skipped for API request:', ['url' => $request->url()]);
            return $next($request);
        }
        return $next($request);
    }
}
