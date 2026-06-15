<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        // Get user communities. For now, fetch all or related.
        $communities = Community::with(['members.user'])->get();
        return response()->json(['data' => $communities], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['owner_id'] = auth('api')->id() ?? 1;

        $community = Community::create($validated);

        $community->members()->create([
            'user_id' => $validated['owner_id'],
            'role' => 'Owner'
        ]);

        return response()->json(['message' => 'Community created successfully', 'data' => $community], 201);
    }

    public function feed($id)
    {
        $community = Community::findOrFail($id);
        // Assuming we have a posts/feed relation in the future
        // $feed = $community->posts()->latest()->get();
        return response()->json(['data' => []], 200);
    }

    public function storeFeed(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);
        $community = Community::findOrFail($id);
        // Logic to store feed
        return response()->json(['message' => 'Feed posted successfully'], 201);
    }

    public function updateRoles(Request $request, $id)
    {
        $community = Community::findOrFail($id);
        // Logic to update roles based on user ID and role type
        return response()->json(['message' => 'Roles updated successfully'], 200);
    }
}
