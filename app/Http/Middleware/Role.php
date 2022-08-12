<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\UserServices;

class Role
{

    public function handle(Request $request, Closure $next, $role)
    {
        $userRole = auth()->user()->role;

        if($userRole !== $role){
            return redirect(UserServices::getDashboardRouteBasedOnUserRole($userRole));
        }
        return $next($request);
    }
}
