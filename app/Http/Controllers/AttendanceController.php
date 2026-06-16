<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        return view('Pages.Attendance', compact('eventId'));
    }
}
