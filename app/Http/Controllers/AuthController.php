<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    // Show registration form
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    // Handle registration form submission
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $role = User::where('role', 'admin')->exists() ? 'student' : 'admin';

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole()->with('success', 'Registration successful. Welcome!');
    }

    // Handle login form submission
    public function login(Request $request)
    {
        // Validate the form input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Try to log the user in
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        // Login failed - go back with error
        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->withInput($request->only('email'));
    }

    // Logout the user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    // Redirect user to correct dashboard based on role
    private function redirectByRole()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
}
