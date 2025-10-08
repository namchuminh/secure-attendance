<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AccessLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AttendanceEmployeeController;
use App\Http\Controllers\SalaryEmployeeController;

// Login & logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// Các route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->middleware('role:admin');
    Route::resource('attendances', AttendanceController::class)->middleware('role:admin');
    Route::resource('salaries', SalaryController::class)->middleware('role:admin');
    Route::get('access-logs', [AccessLogController::class, 'index'])->name('access-logs.index')->middleware('role:admin');

    Route::get('employee/attendances', [AttendanceEmployeeController::class, 'index'])->name('employee.attendances')->middleware('role:employee');
    Route::get('employee/salaries', [SalaryEmployeeController::class, 'index'])->name('employee.salaries')->middleware('role:employee');

    // Hồ sơ cá nhân
    Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::post('profiles/update', [ProfileController::class, 'update'])->name('profiles.update');
});



// // Quản lý nhân viên
// Route::resource('users', UserController::class);

// // Chấm công & điểm danh
// Route::resource('attendances', AttendanceController::class);

// // Quản lý bảng lương
// Route::resource('salaries', SalaryController::class);

// // Nhật ký hệ thống (chỉ xem)
// Route::get('access-logs', [AccessLogController::class, 'index'])->name('access-logs.index');

// // Hồ sơ cá nhân
// Route::get('profiles', [ProfileController::class, 'index'])->name('profiles.index');
// Route::post('profiles/update', [ProfileController::class, 'update'])->name('profiles.update');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings/update', [SettingController::class, 'update'])->name('settings.update');