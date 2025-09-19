<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;
use DB;

class InitCartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cart = Cart::firstOrCreate([
            'id' => $request->session()->get('cart_id')
        ], []);

        DB::table('visit_logs')->insertOrIgnore([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'referrer' => $request->headers->get('referer'),
            'activity' => 'INIT_CART',
            'additional_info' => json_encode(['cart_id' => $cart->id]),
        ]);

        $request->session()->put('cart_id', $cart->id);

        return $next($request);
    }
}
