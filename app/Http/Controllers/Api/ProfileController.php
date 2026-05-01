<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'sometimes|string|max:100',
            'avatar'    => 'sometimes|string', // base64
        ]);

        $user = $request->user();

        if ($request->has('full_name')) {
            $user->full_name = $request->full_name;
        }

        if ($request->has('avatar')) {
            // بررسی سایز (حداکثر 2MB base64)
            if (strlen($request->avatar) > 2 * 1024 * 1024 * 1.37) {
                return response()->json(['message' => 'حجم تصویر بیشتر از 2 مگابایت است'], 422);
            }
            $user->avatar = $request->avatar;
        }

        if ($request->has('password') && $request->password) {
            $request->validate(['password' => 'min:6']);
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'id'        => $user->id,
            'full_name' => $user->full_name,
            'username'  => $user->username,
            'role'      => $user->role?->name,
            'avatar'    => $user->avatar,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');
        return response()->json([
            'id'        => $user->id,
            'full_name' => $user->full_name,
            'username'  => $user->username,
            'role'      => $user->role?->name,
            'avatar'    => $user->avatar,
            'status'    => $user->status,
        ]);
    }
}
