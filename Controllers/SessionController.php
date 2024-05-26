<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(User $user)
    {
        // validate
        $validatedAttributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required',],
        ]);

        // attempt to login the user
        if(! Auth::attempt($validatedAttributes)){
            throw ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match.'
            ]);
        }

        // regenerate the session token
        request()->session()->regenerate();

        // redirect
        if($user->isAdmin())
        {
            return redirect('/admin');
        }
        return redirect('/publishers');
        // redirect
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/');
    }
}
