<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AdminPortalController extends Controller
{
    public function viewPortal()
    {
        $members = User::where('role', 'member')
        ->with('membershipStatus')
        ->latest()
        ->limit(5)
        ->get();
        
        $user = Auth::user();

        

        return view('admins.admin-dashboard', compact('members', 'user'));
    }
}
