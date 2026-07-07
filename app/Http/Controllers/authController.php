<?php

namespace App\Http\Controllers;

use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;

class authController extends Controller
{
    public function login(){
        return view('layout.login');
    }

    public function loginStore(Request $request){
        // Validate the request data
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            // For restaurant users: check subscription expiry first
            if ($user->role === 'restaurant') {
                // Auto-expire subscriptions for this user
                SubscriptionController::autoExpire($user->id);

                // Re-fetch user fresh from DB (status may have changed)
                $user = $user->fresh();

                if ($user->status !== 'active') {
                    auth()->logout();
                    return back()->withErrors([
                        'error' => 'Your subscription has expired. Please contact the administrator to renew your access.',
                    ]);
                }
            }

            // For admin users: just check if account is active
            if ($user->role === 'admin' && $user->status !== 'active') {
                auth()->logout();
                return back()->withErrors([
                    'error' => 'Your account is inactive. Please contact the administrator.',
                ]);
            }

            // Authentication passed
            return redirect()->intended('/dashboard');
        }

        // Authentication failed
        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
