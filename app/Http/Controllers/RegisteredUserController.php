<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the sign up page.
     */
    public function create(): View
    {
        return view('Authentication.SignUp', ['page' => 'Sign Up']);
    }

    /**
     * Create a new account and log the user in.
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
