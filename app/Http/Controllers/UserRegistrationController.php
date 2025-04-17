<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRegistrationController extends Controller
{
    public function showRegistrationForm(string $token)
    {
        $registrationToken = UserRegistrationToken::where('token', $token)->first();

        if (!$registrationToken || !$registrationToken->isValid()) {
            abort(404, 'Invalid or expired registration link.');
        }

        return view('user-details.complete-registration', [
            'token' => $token,
            'email' => $registrationToken->email,
            'name' => $registrationToken->name,
        ]);
    }

    public function completeRegistration(Request $request, string $token)
    {
        $registrationToken = UserRegistrationToken::where('token', $token)->first();

        if (!$registrationToken || !$registrationToken->isValid()) {
            abort(404, 'Invalid or expired registration link.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($request, $registrationToken) {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $registrationToken->delete();
        });

        return redirect()->route('filament.admin.auth.login')
            ->with('status', 'Registration completed successfully. You can now log in.');
    }
}