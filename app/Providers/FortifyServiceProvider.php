<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Custom authentication logic
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // Redirect after login based on role
        Fortify::loginResponseUsing(function (Request $request) {
            $user = $request->user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'manager') {
                return redirect()->intended('/manager/dashboard');
            } else {
                return redirect()->intended('/user/dashboard');
            }
        });

        // Optional: custom failed login message
        Fortify::failedLoginResponseUsing(function (Request $request) {
            return back()->withErrors([
                'password' => 'Email or password is incorrect.',
            ])->withInput($request->only('email'));
        });
    }
}
