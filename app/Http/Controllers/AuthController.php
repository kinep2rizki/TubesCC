<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('Pages.LoginPage');
    }

    public function syncSession(Request $request)
    {
        $token = $request->input('token');
        if (!$token) {
            return response()->json(['success' => false], 400);
        }

        // Verify token with Auth Service
        $userResponse = Http::withToken($token)->get('http://127.0.0.1:8001/api/auth/me');
        if ($userResponse->successful()) {
            $userData = $userResponse->json();
            
            // Set session variables
            $request->session()->put('jwt_token', $token);
            $request->session()->put('user', $userData);
            // Extract role names since API returns array of role objects
            $roles = isset($userData['roles']) ? array_column($userData['roles'], 'name') : [];
            $request->session()->put('user_roles', $roles);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    public function logout(Request $request)
    {
        // Logout from Auth Service
        if (session()->has('jwt_token')) {
            try {
                Http::withToken(session('jwt_token'))->post('http://127.0.0.1:8001/api/auth/logout');
            } catch (\Exception $e) {
                // Ignore
            }
        }

        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
