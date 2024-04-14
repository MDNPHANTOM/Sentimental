<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserBlocked
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->blocked == 1) {
                abort(403, 'User is Blocked');
            }
            return $next($request);
        }

        abort(401, 'Unauthorized access');
    }
}
