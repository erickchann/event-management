<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index() {
        return view('index');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) return redirect()->back()->withErrors(['error' => 'Email or password not correct']);

        return redirect('/events');
    }

    public function logout(Request $request) {
        Auth::logout();
        
        return redirect('/');
    }
}
