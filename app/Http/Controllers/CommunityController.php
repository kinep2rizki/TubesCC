<?php

namespace App\Http\Controllers;

use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        $community = Community::with(['members.user'])->first();
        
        // Fallback if no community exists
        if (!$community) {
            $community = new Community([
                'name' => 'No Community Found', 
                'description' => 'Create a community to get started.'
            ]);
            $community->setRelation('members', collect());
        }

        return view('Pages.Community', compact('community'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Logic to create community
        // $validated['owner_id'] = auth()->id();
        // Community::create($validated);

        return back()->with('success', 'Community created successfully.');
    }

    public function updateRoles(Request $request, $id)
    {
        // Logic to update community roles from the Role Builder Modal
        return back()->with('success', 'Roles updated successfully.');
    }
}
