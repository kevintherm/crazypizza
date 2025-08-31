<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'remember' => 'sometimes|boolean',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                $token = $request->user()->currentAccessToken()?->plainTextToken ?? $request->user()->createToken('api-token')->plainTextToken;

                return ApiResponse::success('Login successful', [
                    'user' => Auth::user(),
                    'token' => $token,
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        if ($request->expectsJson()) {
            return ApiResponse::error('No matching credentials were found. Please try again.', 400);
        }

        return back()->withErrors([
            'message' => 'No matching credentials were found. Please try again.',
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            $request->user()->currentAccessToken()->delete();

            return ApiResponse::success('Logout successful');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
