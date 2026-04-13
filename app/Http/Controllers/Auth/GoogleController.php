<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect('/login')->withErrors('Google authentication failed.');
        }

        if (\App\Models\BlockedEmail::where('email', $googleUser->getEmail())->exists()) {
            return redirect('/login')->withErrors('هذا البريد الإلكتروني محظور من المنصة لمخالفة الشروط.');
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->fill([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'role' => $user->role ?: 'seller',
                'email_verified_at' => now(),
            ]);
            $user->save();
        } else {
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => null,
                'role' => 'seller',
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }
}
