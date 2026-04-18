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
            return redirect()->route('member-portal');
        }

        $members = User::where('role', 'member')
            ->with('membershipStatus')
            ->latest()
            ->limit(8)
            ->get();

        $summary = [
            'total' => User::where('role', 'member')->count(),
            'active' => MembershipStatus::where('status', 'Active')->count(),
            'inactive' => MembershipStatus::where('status', 'Inactive')->count(),
            'suspended' => MembershipStatus::where('status', 'Suspended')->count(),
        ];

        return view('admins.admin-dashboard', compact('members', 'user', 'summary'));
    }
}
