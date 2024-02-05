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
        $user = $request->user();

        if ($user && $user->activated == 1) {
            return $next($request);
        }

        return $this->returnWrong(__('message.cant_proceed'));
    }
}
