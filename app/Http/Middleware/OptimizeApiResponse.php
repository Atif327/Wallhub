<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptimizeApiResponse
{
    /**
     * Optimize API responses to include only necessary fields for grid display
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Enable compression headers for API responses
        if ($request->wantsJson() || $request->is('api/*')) {
            $response->header('Content-Type', 'application/json; charset=utf-8');
            $response->header('Cache-Control', 'public, max-age=300'); // 5 min cache for API
        }

        return $response;
    }
}
