<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Index
    public function index()
    {
        return view('employer.dashboard', [
            'user' => Auth::user(),
        ]);
    }
}
