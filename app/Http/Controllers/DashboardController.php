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
        $user = auth()->user();

        // Nếu là nhân viên
        if ($user->role === 'employee') {
            $today = Carbon::today();
            $month = $today->month;
            $year = $today->year;

            // Số ngày công trong tháng
            $attendanceCount = Attendance::where('user_id', $user->id)
                ->whereMonth('work_date', $month)
                ->whereYear('work_date', $year)
                ->count();

            // Số ngày đi trễ trong tháng
            $lateDays = Attendance::where('user_id', $user->id)
                ->whereMonth('work_date', $month)
                ->whereYear('work_date', $year)
                ->where('status', 'Late')
                ->count();

            // Lương tháng hiện tại
            $salary = Salary::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
            $currentSalary = $salary->total_salary ?? $user->base_salary;

            // Tổng lương 12 tháng
            $salaryStats = Salary::select(DB::raw('month'), DB::raw('SUM(total_salary) as total'))
                ->where('user_id', $user->id)
                ->where('year', $year)
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $monthlySalaries = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthlySalaries[] = $salaryStats[$m] ?? 0;
            }

            // Số ngày làm việc mỗi tháng
            $attendanceStats = Attendance::select(DB::raw('MONTH(work_date) as month'), DB::raw('COUNT(*) as total'))
                ->where('user_id', $user->id)
                ->whereYear('work_date', $year)
                ->groupBy(DB::raw('MONTH(work_date)'))
                ->pluck('total', 'month')
                ->toArray();

            $monthlyAttendance = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthlyAttendance[] = $attendanceStats[$m] ?? 0;
            }

            return view('dashboard.employee', compact(
                'attendanceCount',
                'currentSalary',
                'lateDays',
                'month',
                'year',
                'monthlySalaries',
                'monthlyAttendance'
            ));
        } else {
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
}
