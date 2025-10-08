<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Attendance::where('user_id', $user->id);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ngày làm
        if ($request->filled('date')) {
            $query->whereDate('work_date', $request->date);
        }

        // Phân trang (10 bản ghi/trang)
        $attendances = $query->orderBy('work_date', 'desc')->paginate(10);

        return view('attendances.employee', compact('attendances'));
    }
}
