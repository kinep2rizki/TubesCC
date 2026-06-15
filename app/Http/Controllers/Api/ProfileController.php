<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth('api')->user();
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'avatar_url' => 'nullable|url'
        ]);

        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully', 'data' => $user], 200);
    }

    public function security(Request $request)
    {
        $user = auth('api')->user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['error' => 'Current password does not match'], 400);
        }

        $user->update(['password' => Hash::make($validated['new_password'])]);

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

    public function notifications(Request $request)
    {
        $user = auth('api')->user();
        // Assume user has notification settings in a related table or JSON column
        // Logic to update preferences
        return response()->json(['message' => 'Notification preferences updated successfully'], 200);
    }
}
