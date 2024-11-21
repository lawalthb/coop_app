<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email'),
            function($user, $token) {
                Mail::to($user->email)->send(new ResetPasswordMail($token));
            }
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link has been sent to your email')
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
