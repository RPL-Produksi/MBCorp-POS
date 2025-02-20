<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function indexLogin(Request $request)
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    if (!Auth::attempt($request->only('username', 'password'))) {
        return redirect()->back()->with('error', 'Username atau password salah');
    }

    $user = Auth::user();

    // Cek apakah user ini admin dan apakah ada subscription yang expired
    if ($user->role === 'admin') {
        $subscription = Subscription::where('admin_id', $user->id)->latest()->first();

        if ($subscription && $subscription->expired_at < now()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda sudah expired, silakan hubungi superadmin.');
        }
    }

    // Redirect berdasarkan role
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard')->with('welcome', 'Selamat datang, Anda berhasil login');
    } elseif ($user->role == 'superadmin') {
        return redirect()->route('superadmin.dashboard')->with('welcome', 'Selamat datang, Anda berhasil login');
    } elseif ($user->role == 'owner') {
        return redirect()->route('owner.dashboard')->with('welcome', 'Selamat datang, Anda berhasil login');
    } elseif ($user->role == 'kasir') {
        return redirect()->route('kasir.dashboard', ['mode' => 'list']);
    }
}


    public function changeJustPassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
