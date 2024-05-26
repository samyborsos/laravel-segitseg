<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        // validate
        request()->validate([
            'name'          => ['required'],
            'email'         => ['required', 'email'],
            'password'      => ['required', Password::min(6), 'confirmed'], // laravel/docs/validation
        ]);

        // create that user
        //dd($validatedAttributes);
        //dd(request());
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' =>  Hash::make(request('password')),
            'admin' => false,
        ]);

        // log in
        Auth::login($user);

        // redirect to dashboard
        return redirect('/todos');
    }
}
