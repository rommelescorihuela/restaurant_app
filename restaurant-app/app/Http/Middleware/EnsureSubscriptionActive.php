<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->restaurant_id) {
            return $next($request);
        }

        $restaurant = $user->tenant;

        if (! $restaurant) {
            return $next($request);
        }

        if (! $restaurant->subscription || ! $restaurant->subscription->hasAccess()) {
            return redirect('/app');
        }

        return $next($request);
    }
}
