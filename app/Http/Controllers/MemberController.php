<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\MembershipStatus;


class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::where('role', 'member')
        ->with('membershipStatus')
        ->latest()
        ->get(); 

        return view('admins.admin-member', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.member-management.member-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ValidatedData = $request->validate([
            'name'=>'required|string|Max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:8',
        ]);

        $ValidatedData['password'] = Hash::make($ValidatedData['password']);
        $ValidatedData['role'] = 'member';
        $ValidatedData['profile_picture'] = $request->profile_picture;

        $user = User::create($ValidatedData);
        
        MembershipStatus::create([
            'user_id' => $user->id,
            'planType' => 'None',
            'expiry_date' => null,
            'status' => 'Inactive',
        ]);

        return redirect()->route('memberManagement.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = User::with('membershipStatus')->findOrFail($id);
        return view('admins.member-management.member-edit-profile', compact('member'));
    }

    public function editStatus(string $id){
        $member = User::with('membershipStatus')->findOrFail($id);
        return view('admins.member-management.member-edit-status', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = User::findOrFail($id);

        $member->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('memberManagement.index');
    }

    public function updateStatus(Request $request, string $id){
        $request->validate([
            'status' => 'required',
            'planType' => 'required',
        ]);

        $member = User::findOrFail($id);

        $member->membershipStatus()->update([
            'status' => $request->status,
            'planType' => $request->planType,
        ]);

        return redirect()->route('memberManagement.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = User::findOrFail($id);

        $member->delete();

        return redirect()->route('memberManagement.index');
    }
}
