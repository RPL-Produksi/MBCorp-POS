<?php

namespace App\Http\Controllers;

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

        $auth = Auth::attempt($request->only('username', 'password'));
        if (!$auth) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }

        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->role == 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($user->role == 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
    }
}
