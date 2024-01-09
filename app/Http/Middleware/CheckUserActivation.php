<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActivation
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
            return $next($request);
            $user = $request->user();

            if ($user && $user->activated == 1) {
                return $next($request);
            }

            return $this->returnWrong('You can NOT go forward, you\'re not activated');
    }
}
