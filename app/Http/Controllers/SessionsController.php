<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ]);

        // check if failed to login
        if (!Auth::attempt($attributes)) {
            return back()
                ->withErrors(['password' => 'We were unable to authenticate using the provided credentials.'])
                ->withInput();
        }

        // regenerate the sessions
        $request->session()->regenerate();

        // if success then redirect to homepage
        return redirect()->intended('/')->with('success', 'Welcome back!');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/login');
    }
}
