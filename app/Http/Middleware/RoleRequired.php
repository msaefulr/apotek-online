<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleRequired
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user)
            abort(401);

        $jabatan = $user->jabatan ?? null;

        if (!$jabatan || !in_array($jabatan, $roles, true)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
