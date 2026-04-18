<?php

namespace App\Http\Controllers;

use App\Models\MembershipStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function displayRegister()
    {
        return view('users.register');
    }

    public function registerUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'profile_picture' => 'required|image|mimes:jpeg,png,gif,svg|max:2048',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['role'] = 'member';

        $path = $request->file('profile_picture')->store('profile_picture', 'public');
        $validatedData['profile_picture'] = $path;

        $user = User::create($validatedData);

        MembershipStatus::create([
            'user_id' => $user->id,
            'planType' => 'None',
            'expiry_date' => null,
            'status' => 'Inactive',
        ]);

        return redirect()->route('member.login')->with('success', 'Account created. You can now log in as member.');
    }

    public function displayLogin(?string $role = null)
    {
        $loginRole = in_array($role, ['admin', 'member'], true) ? $role : null;

        return view('users.login', compact('loginRole'));
    }


    public function displayMemberLogin()
    {
        return $this->displayLogin('member');
    }

    public function displayAdminLogin()
    {
        return $this->displayLogin('admin');
    }

    public function loginUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'login_role' => 'required|in:admin,member',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials provided.'])->withInput($request->only('email', 'login_role'));
        }

        if ($user->role !== $validatedData['login_role']) {
            return back()->withErrors([
                'login_role' => 'You are trying to sign in to the wrong portal. Please use the correct login type.',
            ])->withInput($request->only('email', 'login_role'));
        }

        if ($user->role === 'admin') {
            $otpCode = (string) random_int(100000, 999999);

            $request->session()->put('admin_mfa', [
                'user_id' => $user->id,
                'code_hash' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(10)->toDateTimeString(),
            ]);

            Mail::raw("Your Benchz Fitness admin login code is: {$otpCode}. This code expires in 10 minutes.", function ($message) use ($user): void {
                $message->to($user->email)
                    ->subject('Benchz Fitness Admin MFA Code');
            });

            return redirect()->route('admin.mfa.show');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member-portal');
    }

    public function showAdminMfa(Request $request)
    {
        if (! $request->session()->has('admin_mfa')) {
            return redirect()->route('admin.login');
        }

        return view('users.admin-mfa');
    }

    public function verifyAdminMfa(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $mfaSession = $request->session()->get('admin_mfa');

        if (! $mfaSession) {
            return redirect()->route('admin.login');
        }

        if (Carbon::parse($mfaSession['expires_at'])->isPast()) {
            $request->session()->forget('admin_mfa');

            return redirect()->route('admin.login')->withErrors([
                'code' => 'Your verification code has expired. Please log in again.',
            ]);
        }

        if (! Hash::check($request->input('code'), $mfaSession['code_hash'])) {
            return back()->withErrors([
                'code' => 'Invalid verification code. Please try again.',
            ]);
        }

        Auth::loginUsingId($mfaSession['user_id']);
        $request->session()->regenerate();
        $request->session()->forget('admin_mfa');

        return redirect()->route('admin-portal');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
