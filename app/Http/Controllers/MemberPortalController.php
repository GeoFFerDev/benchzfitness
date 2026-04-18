<?php

namespace App\Http\Controllers;

use App\Models\MemberAttendanceLog;
use App\Models\MembershipPlans;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MemberPortalController extends Controller
{
    public function viewPortal()
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'member') {
            return redirect()->route('login.member')->withErrors([
                'email' => 'Member account is required to access the member portal.',
            ]);
        }

        $tiers = MembershipPlans::all();
        $status = $user->membershipStatus;
        $attendances = MemberAttendanceLog::where('user_id', $user->id)->get();

        $daysRemaining = null;
        if ($status && $status->status === 'Active') {
            $daysRemaining = (int) now()->diffInDays(Carbon::parse($status->expiry_date), false);
        }

        return view('members.memberPortal', compact('user', 'tiers', 'status', 'daysRemaining', 'attendances'));
    }
    
    public function viewProfile(){
        $user = auth()->user();
        return view('members.profile-management.view-profile', compact('user'));
    }
}
