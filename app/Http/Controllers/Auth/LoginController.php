<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan username
        $user = Akun::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Login berhasil
            Auth::login($user);

            // Regenerate session untuk security
            $request->session()->regenerate();

            // Debug information
            Log::info('Login successful:', [
                'username' => $user->username,
                'user_type' => $user->user_type,
                'role_id' => $user->role_id,
                'user_id' => $user->user_id
            ]);

            // Redirect berdasarkan user type
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    // Redirect to appropriate dashboard
    protected function redirectToDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Debug current user
        Log::info('Redirecting user:', [
            'username' => $user->username,
            'user_type' => $user->user_type,
            'role_id' => $user->role_id
        ]);

        // Priority 1: Check user_type first
        switch ($user->user_type) {
            case 'admin':
                Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            case 'guru':
                Log::info('Redirecting to guru dashboard');
                return redirect()->route('guru.dashboard');
            case 'siswa':
                Log::info('Redirecting to siswa dashboard');
                return redirect()->route('siswa.dashboard');
        }

        // Priority 2: Fallback to role_id
        switch ($user->role_id) {
            case 1: // Admin
                Log::info('Fallback: Redirecting to admin dashboard by role_id');
                return redirect()->route('admin.dashboard');
            case 2: // Guru
                Log::info('Fallback: Redirecting to guru dashboard by role_id');
                return redirect()->route('guru.dashboard');
            case 3: // Siswa
                Log::info('Fallback: Redirecting to siswa dashboard by role_id');
                return redirect()->route('siswa.dashboard');
            default:
                Log::warning('Unknown role, redirecting to home');
                return redirect('/');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}