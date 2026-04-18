<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlans;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = MembershipPlans::all();
       return view('admins.admin-membership-plan', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.membership-plan-management.membership-plan-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:membership_plans,name',
            'tag' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0|max:1000000',
            'description' => 'nullable|string',
        ]);

        MembershipPlans::create($validatedData);
        return redirect()->route('membershipPlan.index');

    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        

        $plans = MembershipPlans::query()
        ->where('name', 'like', "%{$search}%")
        ->orWhere('tag', 'like', "%{$search}%")
        ->orWhere('duration', 'like', "%{$search}%")
        ->orWhere('price', 'like', "%{$search}%")
        ->orWhere('description', 'like', "%{$search}%")
        ->get();

        return view('admins.membership-plan-management.membership-plan-view', compact('plans'));

    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipPlans $membershipTiers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plan = MembershipPlans::findorFail($id);
        return view('admins.membership-plan-management.membership-plan-edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $membershipPlan)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:membership_plans,name',
            'tag' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0|max:1000000',
            'description' => 'nullable|string',
        ]);

        $plan = MembershipPlans::findOrFail($membershipPlan);
        $plan->update($validatedData);
        
        return redirect()->route('membershipPlan.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipPlans $membershipPlan)
    {
        $membershipPlan->delete();

        return redirect()->route('membershipPlan.index');
    }
}
