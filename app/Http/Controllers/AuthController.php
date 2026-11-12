<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'action' => 'LOGIN',
                'module' => 'Autentikasi',
                'description' => 'User berhasil login ke sistem.',
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang dimasukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        $userName = Auth::user()?->name ?? 'User';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar dari sistem.');
    }

    public function switchRole(Request $request, string $role): RedirectResponse
    {
        $user = User::where('role', $role)->first();

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();

            AuditLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => 'ROLE_SWITCH',
                'module' => 'Autentikasi',
                'description' => "Berganti peran sebagai {$role} ({$user->name}).",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Berhasil berganti peran sebagai: '.ucfirst(str_replace('_', ' ', $role))." ({$user->name})");
        }

        return back()->with('error', "Role {$role} tidak ditemukan.");
    }
}
