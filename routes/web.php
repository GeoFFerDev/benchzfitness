<?php

use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberAttendanceLogsController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\MembershipPurchaseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users.login-role-select');
})->name('login.select');

Route::get('/register', [AuthController::class, 'displayRegister'])->name('register');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register.store');

Route::get('/login', [AuthController::class, 'displayMemberLogin'])->name('login');
Route::get('/login/member', [AuthController::class, 'displayMemberLogin'])->name('login.member');
Route::get('/login/admin', [AuthController::class, 'displayAdminLogin'])->name('login.admin');
Route::post('/login', [AuthController::class, 'loginUser'])->name('login.perform');

Route::get('/admin/mfa', [AuthController::class, 'showAdminMfa'])->name('admin.mfa.show');
Route::post('/admin/mfa/resend', [AuthController::class, 'resendAdminMfa'])->name('admin.mfa.resend');
Route::post('/admin/mfa', [AuthController::class, 'verifyAdminMfa'])->name('admin.mfa.verify');

Route::middleware('auth')->group(function (): void {
    // Member portal
    Route::get('/member/portal', [MemberPortalController::class, 'viewPortal'])->name('member-portal');
    Route::get('/member/portal/profile', [MemberPortalController::class, 'viewProfile'])->name('member.profile');
    Route::get('/member/portal/profile/edit', [ProfileController::class, 'editProfile'])->name('member.profile.edit');
    Route::get('/member/portal/profile/edit/password', [ProfileController::class, 'editPassword'])->name('member.profile.edit.password');
    Route::post('/member/portal/profile/edit/password-update', [ProfileController::class, 'updatePassword'])->name('member.profile.edit.password.update');
    Route::post('/member/portal/profile/update', [ProfileController::class, 'updateProfile'])->name('member.profile.update');

    // Membership purchases
    Route::get('/membership/purchase/{id}', [MembershipPurchaseController::class, 'showPurchase'])->name('membership.purchase');
    Route::post('/membership/purhcase/{tier}', [MembershipPurchaseController::class, 'store'])->name('membership.purchase.store');

    // Admin portal
    Route::get('/admin/portal', [AdminPortalController::class, 'viewPortal'])->name('admin-portal');
    Route::get('/membershipPlan/search', [MembershipPlanController::class, 'search'])->name('membershipPlan.search');
    Route::resource('membershipPlan', MembershipPlanController::class);

    Route::get('memberManagement/{id}/status', [MemberController::class, 'editStatus'])->name('memberManagement.editStatus');
    Route::put('memberManagement/{id}/status', [MemberController::class, 'updateStatus'])->name('memberManagement.updateStatus');
    Route::resource('memberManagement', MemberController::class)->except(['create', 'store']);

    Route::get('admin/attendance-logs', [MemberAttendanceLogsController::class, 'index'])->name('member.attendanceLogs');
    Route::get('admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
