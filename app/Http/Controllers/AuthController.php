<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('logged_in')) {
            return redirect()->route('redirect');
        }
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:opd,publik',
        ]);

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        // simpan session
        session([
            'logged_in' => true,
            'role' => $user->role,
            'user_name' => $user->name ?? $user->username,
            'user_opd' => $user->role === 'opd' ? ($user->name ?? $user->username) : null,
            'username' => $user->username,
            'user_id' => $user->id,
            'opd_id' => $user->opd_id,
        ]);

        return redirect()->route('redirect');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    public function redirectAfterLogin()
    {
        if (!session('logged_in')) {
            return redirect()->route('login');
        }

        return session('role') === 'publik'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('opd.bookings');
    }
}