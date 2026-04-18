<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function editProfile(){
        $user = Auth::user();
        return view('members.profile-management.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
    $user = Auth::user();
    $member = User::find($user->id); // Get the actual Model instance

    $member->name = $request->name;
    $member->email = $request->email;

    if ($request->hasFile('profile_picture')) {
        // Delete old picture if it exists to save storage space
        if ($member->profile_picture) {
            Storage::disk('public')->delete($member->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile_picture', 'public');
        $member->profile_picture = $path;
    }

    $member->save(); 

    return redirect()->route('member.profile')->with('success', 'Profile Updated!');

        
    }

    public function editPassword(){
        return view('members.profile-management.edit-password');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if(!Hash::check($request->current_password, Auth::user()->password)){
            return redirect()->route('member.profile.edit.password');
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        
        User::where('id', $user->id)->update([
            'password' => $user->password,
            'updated_at' => now(),
        ]);

        return redirect()->route('member.profile');
    }

}
