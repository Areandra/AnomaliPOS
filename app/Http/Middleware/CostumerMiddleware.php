<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Session;
use App\Models\TableSession;
use Symfony\Component\HttpFoundation\Response;

class CostumerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionToken = $request->route('sessionToken');

        $currentSession = TableSession::query()->where('token', $sessionToken)
            ->with('restaurant')
            ->firstOrFail();

        $restaurant = $currentSession->restaurant;

        if ($restaurant) {
            Auth::guard('restaurant')->login($restaurant);
        }

        return $next($request);
    }
}
