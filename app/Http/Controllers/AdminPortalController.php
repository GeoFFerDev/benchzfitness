<?php

namespace App\Http\Controllers;

use App\Models\MembershipStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminPortalController extends Controller
{
    public function viewPortal()
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'admin') {
            return redirect()->route('login.admin')->withErrors([
                'email' => 'Admin account is required to access the admin portal.',
            ]);
        }

        $members = User::where('role', 'member')
            ->with('membershipStatus')
            ->latest()
            ->limit(5)
            ->get();

        $summary = [
            'total_members' => User::where('role', 'member')->count(),
            'active_members' => User::where('role', 'member')->whereHas('membershipStatus', fn($q) => $q->where('status', 'Active'))->count(),
            'inactive_members' => User::where('role', 'member')->whereHas('membershipStatus', fn($q) => $q->where('status', 'Inactive'))->count(),
        ];

        return view('admins.admin-dashboard', compact('members', 'user', 'summary'));
    }
}
