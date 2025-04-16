<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                        ->withErrors($validator)
                        ->withInput($request->only('email'));
        }

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');

        // Try authenticating with email first
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Redirect to intended page or default dashboard
            return redirect()->intended('/user-details'); // Redirect to the user details form
        }

        // If email fails, try authenticating with username (assuming 'email' field can contain username)
        // You might need to adjust your User model or logic if username is stored differently
        $credentialsWithUsername = [
            'name' => $request->input('email'), // Assuming 'email' input holds username
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentialsWithUsername, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Redirect to intended page or default dashboard
            return redirect()->intended('/user-details'); // Redirect to the user details form
        }

        // If authentication fails, redirect back with error
        return redirect()->route('login')
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}