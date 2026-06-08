<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
// handle(Request $request, Closure $next, string $role) — the $role is the parameter we'll pass in routes, e.g. role:admin.
// $request->user() — retrieves the currently authenticated user. 
// $request->user()->hasRole($role) — uses the hasRole() helper already on the User model (checks role->name).
// If there's no user or their role doesn't match → abort(403) (forbidden). Otherwise the request continues.