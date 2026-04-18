<?php

namespace App\Http\Controllers;

use App\Models\MemberAttendanceLog;
use App\Models\MembershipPlans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MembershipStatus;
use Carbon\Carbon;

class MemberPortalController extends Controller
{
    public function viewPortal()
    {
        $user = Auth::user();
        $tiers = MembershipPlans::all();
        $status = $user->membershipStatus;

        $attendances = MemberAttendanceLog::where('user_id', $user->id)->get();

    
        $daysRemaining = null;
        if ($status->status === 'Active'){
            $daysRemaining = (int) now()->diffInDays(Carbon::parse($status->expiry_date), false);
        }

        return view('members.memberPortal', compact('user', 'tiers', 'status', 'daysRemaining', 'attendances'));
    }
    
    public function viewProfile(){
        $user = Auth::user();
        return view('members.profile-management.view-profile', compact('user'));
    }
}
