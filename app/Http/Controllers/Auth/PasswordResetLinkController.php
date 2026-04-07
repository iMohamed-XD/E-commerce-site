<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        /** @var User|null $user */
        $user = User::where('email', $request->email)->first();

        // Sellers can request one password-reset email per calendar day.
        if (
            $user &&
            $user->isSeller() &&
            $user->password_reset_requested_at &&
            $user->password_reset_requested_at->isSameDay(Carbon::now())
        ) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'يمكنك طلب إعادة تعيين كلمة المرور مرة واحدة يومياً. حاول مرة أخرى غداً.']);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT && $user && $user->isSeller()) {
            $user->forceFill([
                'password_reset_requested_at' => now(),
            ])->save();
        }

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
