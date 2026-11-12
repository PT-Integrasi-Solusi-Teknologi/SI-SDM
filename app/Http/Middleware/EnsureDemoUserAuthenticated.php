<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDemoUserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            $defaultUser = User::where('role', 'admin_sdm')->first() ?? User::first();
            if ($defaultUser) {
                Auth::login($defaultUser);
            }
        }

        return $next($request);
    }
}
