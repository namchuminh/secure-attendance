<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Salary;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalUsers = User::count();
        $todayAttendance = Attendance::whereDate('work_date', $today)->count();
        $monthSalary = Salary::whereMonth('created_at', $today->month)->sum('total_salary');

        return view('dashboard.index', compact('totalUsers', 'todayAttendance', 'monthSalary'));
    }
}
