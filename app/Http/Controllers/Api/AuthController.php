<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:4|max:100',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            \Log::warning('Failed login attempt', [
                'username' => $request->username,
                'ip'       => $request->ip(),
            ]);
            return response()->json(['message' => 'نام کاربری یا رمز عبور اشتباه است'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'حساب کاربری شما غیرفعال است'], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'username'  => $user->username,
                'role'      => $user->role?->name,
                'avatar'    => $user->avatar,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['message' => 'خروج موفق']);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');
        return response()->json([
            'id'        => $user->id,
            'full_name' => $user->full_name,
            'username'  => $user->username,
            'role'      => $user->role?->name,
            'role_id'   => $user->role_id,
            'status'    => $user->status,
            'avatar'    => $user->avatar,
        ]);
    }
}
