<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 10, int $decaySeconds = 60, string $name = 'rate_limit'): Response
    {
        $key = optional($request->user())->id ?? optional($request->session())->getId() ?? $request->ip();

        return RateLimiter::attempt(
            key: "$name:$key",
            maxAttempts: $maxAttempts,
            decaySeconds: $decaySeconds,
            callback: function () use ($next, $request) {
                return $next($request);
            }
        ) ?: abort(429, 'Slow down! Too many requests.');
    }
}
