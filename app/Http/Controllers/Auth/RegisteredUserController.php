<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\PhoneNumber;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzQwOTM1NzQsImlhdCI6MTczMTUwMTU3NCwicm9sZSI6InRlc3QiLCJzaWduIjoiYmFmMGEyOTI1ZWNlMThiNjljNzE0ZmI5ZWFjMzY4YThlNzI3M2VhODAwYmE2MGIxOTJmZjc5NzMyOGE3YjM2NSIsInN1YiI6Ijg5MjIifQ.2NkUcTyrrEzV4FtG5diB7__cQMRMcSdqboue0IFvLT0";

        $data = [
            'mobile_phone' => $request->phone,
            'message' => 'Bu Eskiz dan test',
            'from' => 4546,
            'callback_url' => 'http://127.0.0.1:8000/',
        ];

        PhoneNumber::dispatch($token, $data);

        return redirect(route('dashboard', absolute: false));
    }
}
