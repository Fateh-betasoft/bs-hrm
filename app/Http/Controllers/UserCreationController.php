<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserMeta;

class UserCreationController extends Controller
{
    /**
     * Handle the form submission and send email.
     */
    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $slug = Str::slug($validatedData['email']);
        $token = Str::random(32);

        // Store the token and slug temporarily
        UserMeta::create([
            'email_slug' => $slug,
            'token' => $token,
        ]);

        try {
            // Send email to admin
            Mail::send('emails.user_creation', ['slug' => $slug, 'token' => $token], function ($message) use ($validatedData) {
                $message->to(config('mail.admin_email', 'admin@example.com'))
                        ->subject('New User Creation Request');
            });

            return response()->json(['message' => 'Email sent to admin.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send email. Please try again later.'], 500);
        }
    }

    /**
     * Handle the user details form.
     */
    public function userDetails($slug, Request $request)
    {
        $userMeta = UserMeta::where('email_slug', $slug)->firstOrFail();

        if ($request->token !== $userMeta->token) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        User::create($validatedData);

        return response()->json(['message' => 'User created successfully.']);
    }
}