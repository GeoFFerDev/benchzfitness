<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;
use App\models\MembershipStatus;
use GuzzleHttp\Psr7\Query;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function index(){
        $user = Auth::user();

    }

    public function displayRegister(){
        return view('users.register');
    }
    
    public function registerUser(Request $request){

        $ValidatedData = $request->validate([
            'name'=>'required|string|Max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:8',
            'profile_picture' => 'required|image|mimes:jpeg,png,gif,svg|max:2048',
        ]);

        $ValidatedData['password'] = Hash::make($ValidatedData['password']);
        $ValidatedData['role'] = 'member';

        $path = $request->file('profile_picture')->store('profile_picture', 'public');
        $ValidatedData['profile_picture'] = $path;

        $user = User::create($ValidatedData);
        
        MembershipStatus::create([
            'user_id' => $user->id,
            'planType' => 'None',
            'expiry_date' => null,
            'status' => 'Inactive',
        ]);

        return redirect()->route('login');
    }

    public function displayLogin(){
        return view('users.login');
    }

    public function loginUser(Request $request){
        $ValidatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($ValidatedData)){
            $request->session()->regenerate();

            if(Auth::user()->role == "admin"){
                return redirect()->route('admin-portal');
            }

            return redirect()->route('member-portal');
        
            //redirect na sa portal
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
