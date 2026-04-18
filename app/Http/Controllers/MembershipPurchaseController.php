<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\MembershipPlans;
use App\Models\MembershipPurchase;
use App\Models\MembershipStatus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MembershipPurchaseController extends Controller
{
    public function showPurchase($id){
        $tier = MembershipPlans::findOrFail($id);
        return view('members.purchase-membership.purchase-tier', compact('tier'));
    }
    public function store(MembershipPlans $tier){
        $user = Auth::user();

        $expiresAt = Carbon::now()->addDays($tier->duration);

        MembershipPurchase::create([
            'user_id' => $user->id,
            'membership_tier_id' => $tier->id,
            'price' => $tier->price,
            'duration' => $tier->duration,
            'purchased_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        MembershipStatus::where('user_id', $user->id)
        ->update([
            'planType' => $tier->name,
            'expiry_date' => $expiresAt,
            'status' => 'Active',
            'start_date' => now(),
        ]);

        return redirect()->route('member-portal');


    }
}
