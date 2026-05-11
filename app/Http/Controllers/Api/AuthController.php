<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login and get token
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required|string',
            'device_name' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'error' => 'Account deactivated.'
            ], 403);
        }

        // Delete old tokens for this device
        $user->tokens()->where('name', $request->device_name)->delete();

        // Create new token with scopes and 30-day expiry
        $token = $user->createToken(
            $request->device_name,
            ['expense:read', 'expense:write', 'summary:read'],
            now()->addDays(30)
        );

        return response()->json([
            'token'      => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => now()->addDays(30)->toDateTimeString(),
            'scopes'     => ['expense:read', 'expense:write', 'summary:read'],
            'user'       => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 200);
    }

    // Logout and revoke token
    public function logout(Request $request)
    {
        // Revoke current token only
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ], 200);
    }

    // Get current user info
    public function me(Request $request)
    {
        return response()->json([
            'id'         => $request->user()->id,
            'name'       => $request->user()->name,
            'email'      => $request->user()->email,
            'role'       => $request->user()->role,
            'is_active'  => $request->user()->is_active,
            'created_at' => $request->user()->created_at->toDateTimeString(),
        ]);
    }

    // Get all tokens for current user
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens()->get()->map(function($token) {
            return [
                'id'         => $token->id,
                'name'       => $token->name,
                'abilities'  => $token->abilities,
                'expires_at' => $token->expires_at?->toDateTimeString(),
                'created_at' => $token->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'tokens' => $tokens
        ], 200);
    }

    // Revoke all tokens
    public function revokeAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'All tokens revoked successfully.'
        ], 200);
    }
}
