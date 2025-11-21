<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

            return back()->withErrors([
                'password' => 'Email or password is incorrect.',
            ])->withInput();
        }

        // redirect by role
        $user = Auth::user();

        return $user->role === 'admin'
            ? redirect('/dashboard')
            : redirect('/user/dashboard');
    }
}
