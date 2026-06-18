<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * Get users from Auth Service, memberships from Project Service, and stitch them.
     */
    public function index(Request $request)
    {
        $token = session('jwt_token') ?? '';
        $communityId = $request->input('community_id', 'all');
        $search = $request->input('search', '');

        // 1. If filtering by community, get its members first
        $filterUserIds = null;
        if ($communityId !== 'all') {
            $projResponse = Http::withToken($token)
                ->get("http://127.0.0.1:8002/api/communities/{$communityId}/members");
            if ($projResponse->successful()) {
                $membersData = $projResponse->json()['data'] ?? [];
                $filterUserIds = array_column($membersData, 'user_id');
                // If the community has no members, we shouldn't fetch any users
                if (empty($filterUserIds)) {
                    $filterUserIds = [0]; // Dummy ID so it returns empty paginator
                }
            }
        }

        // 2. Fetch Users from Auth Service (with user_ids filter if applicable)
        $authParams = [
            'search' => $search,
            'page' => $request->input('page', 1)
        ];
        if ($filterUserIds !== null) {
            $authParams['user_ids'] = $filterUserIds;
        }

        $authResponse = Http::withToken($token)
            ->get('http://127.0.0.1:8001/api/auth/users', $authParams);

        if (!$authResponse->successful()) {
            return redirect()->back()->with('error', 'Failed to fetch users from Auth Service.');
        }

        $authData = $authResponse->json();
        $usersArray = $authData['data']['data'] ?? [];
        $userIds = array_column($usersArray, 'id');

        // 3. Fetch Memberships from Project Service for the users on this page
        $membershipsData = [];
        if (!empty($userIds)) {
            $projResponse = Http::withToken($token)
                ->post('http://127.0.0.1:8002/api/users/memberships', [
                    'user_ids' => $userIds
                ]);
            if ($projResponse->successful()) {
                $membershipsData = $projResponse->json()['data'] ?? [];
            }
        }

        // 3. Data Stitching
        // Group memberships by user_id
        $membershipsByUser = [];
        foreach ($membershipsData as $m) {
            $membershipsByUser[$m['user_id']][] = json_decode(json_encode($m));
        }

        // Convert user arrays to objects so Blade template ($u->name) works smoothly
        $usersCollection = collect($usersArray)->map(function ($u) use ($membershipsByUser) {
            $userObj = (object) $u;
            // Spatie roles from Auth Service
            $userObj->roles = collect($u['roles'] ?? [])->map(fn($r) => (object) $r);
            // Memberships from Project Service
            $userObj->communityMemberships = collect($membershipsByUser[$userObj->id] ?? []);
            
            // Helper method for the view
            $userObj->hasRole = function($roleName) use ($userObj) {
                return $userObj->roles->contains('name', $roleName);
            };

            // Format date for the view
            $userObj->created_at = \Carbon\Carbon::parse($userObj->created_at);

            return $userObj;
        });

        // 4. Create Paginator
        $users = new LengthAwarePaginator(
            $usersCollection,
            $authData['data']['total'] ?? 0,
            $authData['data']['per_page'] ?? 15,
            $authData['data']['current_page'] ?? 1,
            ['path' => url()->current(), 'query' => request()->query()]
        );

        // 5. Fetch all communities for the dropdown
        $communitiesResponse = Http::withToken($token)->get('http://127.0.0.1:8002/api/communities/all');
        $communitiesArray = $communitiesResponse->successful() ? ($communitiesResponse->json()['data'] ?? []) : [];
        $communities = collect($communitiesArray)->map(fn($c) => (object) $c);

        // User permissions (Simplified for UI logic)
        $isSuperAdmin = collect(session('user_roles', []))->contains('Super Admin');
        $isOwner = true; // In full implementation, this should check if auth user is Owner of $communityId

        return view('Pages.Users', compact('users', 'communities', 'communityId', 'isSuperAdmin', 'isOwner'));
    }

    /**
     * Update global role in Auth Service and community roles in Project Service.
     */
    public function updateRole(Request $request, $id)
    {
        $token = session('jwt_token') ?? '';
        
        // 1. Update Global Role (Super Admin) if provided
        if ($request->has('role')) {
            Http::withToken($token)->put("http://127.0.0.1:8001/api/auth/users/{$id}/role", [
                'role' => $request->input('role')
            ]);
        }

        // 2. Update Community Roles
        if ($request->has('communities') && is_array($request->input('communities'))) {
            foreach ($request->input('communities') as $communityId => $role) {
                Http::withToken($token)->put("http://127.0.0.1:8002/api/communities/{$communityId}/users/{$id}/role", [
                    'role' => $role
                ]);
            }
        }

        return redirect()->back()->with('success', 'User roles updated successfully.');
    }
}
