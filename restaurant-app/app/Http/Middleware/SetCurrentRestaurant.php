<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentRestaurant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! tenancy()->initialized && $user = $request->user()) {
            if ($user->restaurant_id) {
                $tenantModel = config('tenancy.tenant_model');
                $tenant = $tenantModel::find($user->restaurant_id);

                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
            }
        }

        return $next($request);
    }
}
