<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Redirect to Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Check if user exists
            $user = User::where('email', $googleUser->email)
                ->first();

            if ($user) {
                // User exists - check if active
                if (!$user->is_active) {
                    return redirect()->route('login')
                        ->withErrors([
                            'email' => 'Your account has
                                        been deactivated.'
                        ]);
                }

                // Update google info
                $user->update([
                    'email_verified_at' => $user->email_verified_at
                        ?? now(),
                ]);

                Auth::login($user);

            } else {
                // Create new user from Google
                $user = User::create([
                    'name'              => $googleUser->name,
                    'email'             => $googleUser->email,
                    'password'          => bcrypt(Str::random(16)),
                    'role'              => 'user',
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]);

                Auth::login($user);
            }

            return redirect()->route('dashboard')
                ->with('success',
                    'Welcome ' . $user->name . '! 👋');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Google login failed.
                                Please try again.'
                ]);
        }
    }
}
