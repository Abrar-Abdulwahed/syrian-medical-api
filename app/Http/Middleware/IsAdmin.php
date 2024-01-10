<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\AdminRole;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    use ApiResponseTrait;
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->role === AdminRole::SUPER_ADMIN->value)
            return $next($request);
        return $this->returnWrong($request->user());

    }
}
