<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 5 || Auth::user()->role_id == 6 || Auth::user()->role_id == 7) {
                // Allow access to /dashboard and /order routes only
                $allowedRoutes = ['dashboard', 'order', 'profile.edit'];

                if (in_array($request->route()->getName(), $allowedRoutes)) {
                    return $next($request);
                } else {
                    // Abort with 404 if trying to access unauthorized routes
                    abort(404);
                }
            }elseif (Auth::user()->role_id == 3) {
                // Allow access to /dashboard and /Qc routes only
                $allowedRoutes = ['dashboard', 'Qc-Sheets', 'profile.edit'];

                if (in_array($request->route()->getName(), $allowedRoutes)) {
                    return $next($request);
                } else {
                    // Abort with 404 if trying to access unauthorized routes
                    abort(404);
                }
            }else {
                // Allow access to all routes for other roles
                return $next($request);
            }
        }

        // Redirect to login if not authenticated
        return redirect('/login');
    }
}
