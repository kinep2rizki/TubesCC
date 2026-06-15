<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        $communities = \App\Models\Community::select('id', 'name', 'password')->get();
        // Mask the password so we only send a boolean flag whether it requires a password
        $communities->transform(function ($community) {
            $community->requires_password = !empty($community->password);
            unset($community->password);
            return $community;
        });

        return view('Pages.LoginPage', compact('communities'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'community_id' => ['nullable', 'exists:communities,id'],
            'community_password' => ['nullable', 'string'],
        ]);

        // Validate community password if a community is selected
        $communityToJoin = null;
        if (!empty($validated['community_id'])) {
            $communityToJoin = \App\Models\Community::find($validated['community_id']);
            
            if (!empty($communityToJoin->password)) {
                if (empty($validated['community_password']) || !\Illuminate\Support\Facades\Hash::check($validated['community_password'], $communityToJoin->password)) {
                    return back()->withErrors([
                        'community_password' => 'Incorrect community password.',
                    ])->withInput();
                }
            }
        }

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);
        
        // $user->assignRole('Event Staff'); // Removed to avoid RoleDoesNotExist exception

        if ($communityToJoin) {
            $communityToJoin->members()->create([
                'user_id' => $user->id,
                'role' => 'Member'
            ]);
            session(['active_community_id' => $communityToJoin->id]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
