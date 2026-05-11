<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    // Show 2FA enable page with QR code
    public function enable()
    {
        $user   = auth()->user();
        $google = new Google2FA();
        $secret = $google->generateSecretKey();

        // Store secret in session
        session(['2fa_secret' => $secret]);

        // Generate QR code URL
        $qrCodeUrl = $google->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('2fa.setup', compact('secret', 'qrCodeUrl'));
    }

    // Verify and activate 2FA
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $secret = session('2fa_secret');

        if (!$secret) {
            return redirect()->route('2fa.enable')
                ->withErrors([
                    'code' => 'Session expired. Please try again.'
                ]);
        }

        // Clean the code - remove spaces and dashes
        $code = preg_replace('/[\s-]/', '', $request->code);

        $google = new Google2FA();

        // Allow 4 windows (2 minutes) for time drift
        $google->setWindow(4);

        $valid = $google->verifyKey($secret, $code);

        if (!$valid) {
            return back()->withErrors([
                'code' => 'Invalid code. Please wait for a new
                           code and try again immediately.'
            ])->withInput();
        }

        // Save to database
        auth()->user()->forceFill([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(
                json_encode(
                    collect(range(1, 8))->map(function() {
                        return Str::random(5) . '-' . Str::random(5);
                    })->all()
                )
            ),
            'two_factor_confirmed_at' => now(),
        ])->save();

        session()->forget('2fa_secret');

        return redirect()->route('profile.show')
            ->with('success', '✅ 2FA enabled successfully!
                               Your account is now more secure!');
    }

    // Disable 2FA
    public function disable(Request $request)
    {
        auth()->user()->forceFill([
            'two_factor_secret'          => null,
            'two_factor_recovery_codes'  => null,
            'two_factor_confirmed_at'    => null,
        ])->save();

        return redirect()->route('profile.show')
            ->with('success', '2FA has been disabled.');
    }
}
