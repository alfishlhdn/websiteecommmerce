<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect('/Masuk')->with('error', 'Silakan login terlebih dahulu.');
        }

        $rolesArray = explode('|', $roles);

        if (!in_array(Auth::user()->role, $rolesArray)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}

