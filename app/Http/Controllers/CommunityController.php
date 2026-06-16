<?php

namespace App\Http\Controllers;

use App\Models\Community;

class CommunityController extends Controller
{
    public function index()
    {
        // Data di-fetch melalui JavaScript (Client-Side Rendering)
        // di halaman Blade menggunakan Alpine.js & Fetch API.
        return view('Pages.Community');
    }
}
