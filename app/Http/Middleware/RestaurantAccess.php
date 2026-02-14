<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Super admin can access everything
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }
        
        // Admin can access their restaurant
        if ($user->hasRole('admin') && $user->restaurant_id) {
            return $next($request);
        }
        
        // Waiter and Kitchen staff can only access their restaurant
        if (($user->hasRole('waiter') || $user->hasRole('kitchen')) && $user->restaurant_id) {
            return $next($request);
        }
        
        // Customer can access public routes
        if ($user->hasRole('customer')) {
            return $next($request);
        }
        
        abort(403, 'You do not have permission to access this resource.');
    }
}