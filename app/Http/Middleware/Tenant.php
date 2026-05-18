<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class Tenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mengambil user yang sedang login dari guard 'restaurant'
        $user = Auth::guard('restaurant')->user();

        // Menyimpan restaurant_id ke dalam Context Laravel
        Context::add('restaurant_id', $user?->id);

        return $next($request);
    }
}
