<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Check berdasarkan user_type
        if ($user->user_type === $role) {
            return $next($request);
        }

        // Fallback: Check berdasarkan role_id
        $roleMap = [
            'admin' => 1,
            'guru' => 2,
            'siswa' => 3
        ];

        if (isset($roleMap[$role]) && $user->role_id === $roleMap[$role]) {
            return $next($request);
        }

        // Jika tidak memiliki akses, redirect ke dashboard yang sesuai
        return $this->redirectToDashboard($user);
    }

    protected function redirectToDashboard($user)
    {
        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'guru':
                return redirect()->route('guru.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                // Fallback berdasarkan role_id
                switch ($user->role_id) {
                    case 1:
                        return redirect()->route('admin.dashboard');
                    case 2:
                        return redirect()->route('guru.dashboard');
                    case 3:
                        return redirect()->route('siswa.dashboard');
                    default:
                        return redirect('/');
                }
        }
    }
}