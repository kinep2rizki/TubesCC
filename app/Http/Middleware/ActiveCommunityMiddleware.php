<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveCommunityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('jwt_token') && (!session()->has('active_community_id') || !session()->has('user_memberships'))) {
            try {
                $response = \Illuminate\Support\Facades\Http::withToken(session('jwt_token'))
                    ->get('http://127.0.0.1:8002/api/communities/my-memberships');
                
                if ($response->successful()) {
                    $memberships = $response->json('data', []);
                    if (!empty($memberships)) {
                        // Store the complete memberships array in session
                        session(['user_memberships' => $memberships]);
                        session(['user_communities' => array_column($memberships, 'community')]);
                        
                        // Set the first community as active if not set yet
                        if (!session()->has('active_community_id')) {
                            session(['active_community_id' => $memberships[0]['community_id']]);
                            session(['active_community_data' => $memberships[0]['community']]);
                            session(['active_community_role' => $memberships[0]['role']]);
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // Handle community switching dynamically from ?community_id= URL parameter
        if ($request->has('community_id')) {
            $requestedId = $request->query('community_id');
            $memberships = session('user_memberships', []);
            
            $found = false;
            foreach ($memberships as $membership) {
                if ($membership['community_id'] == $requestedId) {
                    session(['active_community_id' => $membership['community_id']]);
                    session(['active_community_data' => $membership['community']]);
                    session(['active_community_role' => $membership['role']]);
                    $found = true;
                    break;
                }
            }

            // If community is not found in session memberships (e.g. newly created), refetch from backend
            if (!$found && session()->has('jwt_token')) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withToken(session('jwt_token'))
                        ->get('http://127.0.0.1:8002/api/communities/my-memberships');
                    
                    if ($response->successful()) {
                        $memberships = $response->json('data', []);
                        session(['user_memberships' => $memberships]);
                        session(['user_communities' => array_column($memberships, 'community')]);
                        
                        foreach ($memberships as $membership) {
                            if ($membership['community_id'] == $requestedId) {
                                session(['active_community_id' => $membership['community_id']]);
                                session(['active_community_data' => $membership['community']]);
                                session(['active_community_role' => $membership['role']]);
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        }

        // Share the active community to all views
        $activeCommunity = session()->has('active_community_id') && session()->has('active_community_data') 
            ? session('active_community_data') 
            : null;
            
        view()->share('activeCommunity', $activeCommunity);

        return $next($request);
    }
}
