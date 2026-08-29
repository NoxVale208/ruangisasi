<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        
        $roles = array_map('trim', explode(',', $role));

        if (!in_array(Auth::user()->role, $roles, true)) {
            $target = match (Auth::user()->role) {
                'super_admin' => route('superadmin.dashboard'),
                'admin' => route('admin.dashboard'),
                default => route('user.dashboard'),
            };

            return redirect($target)->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
