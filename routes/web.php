<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\MembershipPurchaseController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberAttendanceLogsController;

use Faker\Guesser\Name;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Termwind\Components\Raw;


Route::get('/', function () {
    return view('users.login');
});
Route::get('/adminlayout', function () {
    return view('layouts.admin.admin-layout');
});
Route::get('/memberlayout', function () {
    return view('layouts.member.member-layout');
});

Route::get('/login', function () {
    return view('users.login');
})->name('login');

Route::get('/register', function () {
    return view('users.register');
})->name('register');


Route::get('/members', function () {
    return view('members.memberPortal');
});


Route::get('/register', [AuthController::class, 'displayRegister'])->name('register');
Route::post('/register', [AuthController::class, 'registerUser']);

Route::get('/login', [AuthController::class, 'displayLogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);

//member  portal
Route::get('/member/portal', [MemberPortalController::class, 'viewPortal'])
->name('member-portal');

//member profile Management
Route::get('/member/portal/profile', [MemberPortalController::class, 'viewProfile'])
->name('member.profile');

Route::get('/member/portal/profile/edit', [ProfileController::class, 'editProfile'])
->name('member.profile.edit');

Route::get('/member/portal/profile/edit/password', [ProfileController::class, 'editPassword'])
->name('member.profile.edit.password');

Route::post('/member/portal/profile/edit/password-update', [ProfileController::class, 'updatePassword'])
->name('member.profile.edit.password.update');

Route::post('/member/portal/profile/update', [ProfileController::class, 'updateProfile'])
->name('member.profile.update');





//membership dummy purchase
Route::get('/membership/purchase/{id}', [MembershipPurchaseController::class, 'showPurchase'])
->name('membership.purchase');

Route::POST('/membership/purhcase/{tier}', [MembershipPurchaseController::class, 'store'])
->name('membership.purchase.store');


//admin portal
Route::get('/admin/portal', [AdminPortalController::class, 'viewPortal'])
->name('admin-portal');

Route::get('/membershipPlan/search', [MembershipPlanController::class, 'search'])
->name('membershipPlan.search');

//membership management
Route::resource('membershipPlan', MembershipPlanController::class);

//member management

route::get('memberManagement/{id}/status', [MemberController::class, 'editStatus'])
->name('memberManagement.editStatus');
route::put('memberManagement/{id}/status', [MemberController::class, 'updateStatus'])
->name('memberManagement.updateStatus');

Route::resource('memberManagement', MemberController::class);


//logout

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//Attendance logs
Route::get('admin/attendance-logs', [MemberAttendanceLogsController::class, 'index'])
->name('member.attendanceLogs');

Route::get('admin/profile', [AdminProfileController::class, 'index'])
->name('admin.profile');









