<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in AND is an admin
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Access denied. Admins only.');
        }

        // Check if admin account is active
        if (!$request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        return $next($request);
    }
}
