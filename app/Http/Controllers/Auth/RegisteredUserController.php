<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): InertiaResponse
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) {
                    if (\App\Models\BlockedEmail::where('email', $value)->exists()) {
                        $fail('هذا البريد الإلكتروني محظور من المنصة لمخالفة الشروط.');
                    }
                },
            ],
            'phone_number' => ['required', 'string', 'min:7', 'max:32'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:seller,simple_buyer'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => trim((string) $request->phone_number),
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);
        event(new Registered($user));

        if ($request->header('X-Inertia')) {
            return Inertia::location(route('dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
