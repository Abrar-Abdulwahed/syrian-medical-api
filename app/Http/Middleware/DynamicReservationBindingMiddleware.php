<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DynamicReservationBindingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $instance = Reservation::findOrFail($request->route('item'));

        $request->merge(['reservation' => $instance]);

        return $next($request);
    }
}
