<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalUsers = User::count();
        $todayAttendance = Attendance::whereDate('work_date', $today)->count();
        $monthSalary = Salary::whereMonth('created_at', $today->month)->sum('total_salary');

        $recentLogs = AccessLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        // Thống kê tổng lương theo tháng trong năm hiện tại
        $year = $today->year;
        $salaryStats = Salary::select(
                DB::raw('month'),
                DB::raw('SUM(total_salary) as total')
            )
            ->where('year', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Đảm bảo đủ 12 tháng, gán 0 cho tháng không có dữ liệu
        $monthlySalaries = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlySalaries[] = $salaryStats[$m] ?? 0;
        }

        return view('dashboard.index', compact(
            'totalUsers',
            'todayAttendance',
            'monthSalary',
            'recentLogs',
            'monthlySalaries'
        ));
    }
}
