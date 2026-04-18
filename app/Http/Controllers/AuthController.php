<?php

namespace App\Http\Controllers;

use App\Models\MembershipStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function index()
    {
        return Auth::user();
    }

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
        $validatedData['profile_picture'] = $request->file('profile_picture')->store('profile_picture', 'public');

        $user = User::create($validatedData);

        MembershipStatus::create([
            'user_id' => $user->id,
            'planType' => 'None',
            'expiry_date' => null,
            'status' => 'Inactive',
        ]);

        return redirect()->route('login.member');
    }

    public function displayLogin()
    {
        return $this->displayMemberLogin();
    }

    public function displayMemberLogin()
    {
        return view('users.login', [
            'loginRole' => 'member',
            'loginTitle' => 'Member Login',
            'switchLabel' => 'Admin login',
            'switchRoute' => route('login.admin'),
        ]);
    }

    public function displayAdminLogin()
    {
        return view('users.login', [
            'loginRole' => 'admin',
            'loginTitle' => 'Admin Login',
            'switchLabel' => 'Member login',
            'switchRoute' => route('login.member'),
        ]);
    }

    public function loginUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'login_role' => 'required|in:member,admin',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials provided.'])->withInput($request->only('email'));
        }

        if ($validatedData['login_role'] !== $user->role) {
            return back()->withErrors([
                'email' => $validatedData['login_role'] === 'admin'
                    ? 'This account is not an admin account.'
                    : 'This account is not a member account.',
            ])->withInput($request->only('email', 'login_role'));
        }

        if ($user->role === 'admin') {
            $this->sendAdminMfaCode($request, $user);

            return redirect()->route('admin.mfa.show');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member-portal');
    }

    public function showAdminMfa(Request $request)
    {
        if (! $request->session()->has('admin_mfa')) {
            return redirect()->route('login.admin');
        }

        return view('users.admin-mfa');
    }

    public function resendAdminMfa(Request $request)
    {
        $mfaSession = $request->session()->get('admin_mfa');

        if (! $mfaSession) {
            return redirect()->route('login.admin')->withErrors([
                'email' => 'Your admin login session expired. Please sign in again.',
            ]);
        }

        $user = User::find($mfaSession['user_id']);

        if (! $user || $user->role !== 'admin') {
            $request->session()->forget('admin_mfa');

            return redirect()->route('login.admin')->withErrors([
                'email' => 'Admin account no longer available. Please sign in again.',
            ]);
        }

        $this->sendAdminMfaCode($request, $user);

        return back()->with('mfa_status', 'A new code has been sent.');
    }

    public function verifyAdminMfa(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $mfaSession = $request->session()->get('admin_mfa');

        if (! $mfaSession) {
            return redirect()->route('login.admin');
        }

        if (Carbon::parse($mfaSession['expires_at'])->isPast()) {
            $request->session()->forget('admin_mfa');

            return redirect()->route('login.admin')->withErrors([
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

        return redirect()->route('login.member');
    }

    private function sendAdminMfaCode(Request $request, User $user): void
    {
        $otpCode = (string) random_int(100000, 999999);

        $request->session()->put('admin_mfa', [
            'user_id' => $user->id,
            'code_hash' => Hash::make($otpCode),
            'expires_at' => now()->addMinutes(10)->toDateTimeString(),
        ]);

        try {
            Mail::raw("Your Benchz Fitness admin login code is: {$otpCode}. This code expires in 10 minutes.", function ($message) use ($user): void {
                $message->to($user->email)->subject('Benchz Fitness Admin MFA Code');
            });
        } catch (\Throwable $exception) {
            Log::warning('Unable to send admin MFA code email.', [
                'admin_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);
        }

        if (app()->environment(['local', 'testing']) || config('mail.default') === 'log') {
            $request->session()->flash('admin_mfa_preview', $otpCode);
        }
    }
}
