<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
 public function showLoginForm()
    {
        return view('admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        //  dd($request);


        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // dd($user->role);
                session()->put('user', $user);
            if ($user->role == 'admin') {
            
                return redirect()->route('app.admin-dashboard');
               
            } elseif ($user->role === 'manager') {
                return redirect()->route('manager.dashboard');
            } else {
                // return redirect()->route('app.admin-dashboard');
                echo ' check Your role';
            }
        }

        return back()->withErrors([
            'email' => 'Invalid login details',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}
