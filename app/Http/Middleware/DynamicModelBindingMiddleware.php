<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class DynamicModelBindingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $type = $request->route('type');
        $item = $request->route('item');

        if ($type === 'product') {
            $itemModel = Product::findOrFail($item);
        } elseif ($type === 'service') {
            $itemModel = ProviderService::findOrFail($item);
        } else {
            // Handle other types or throw an exception
            return response()->json(['error' => 'Invalid item type'], 400);
        }

        $request->merge(['item' => $itemModel]);

        return $next($request);
    }
}
