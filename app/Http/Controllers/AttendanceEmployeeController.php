<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AccessLog;

class AttendanceEmployeeController extends Controller
{
    protected function logAction($action, Request $request)
    {
        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Attendance::where('user_id', $user->id);

        $logDetails = [];

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $logDetails[] = "Trạng thái: {$request->status}";
        }

        if ($request->filled('date')) {
            $query->whereDate('work_date', $request->date);
            $logDetails[] = "Ngày làm: {$request->date}";
        }

        $attendances = $query->orderBy('work_date', 'desc')->paginate(10);

        if (!empty($logDetails)) {
            $detailText = implode(', ', $logDetails);
            $this->logAction("Tìm kiếm chấm công cá nhân ({$detailText})", $request);
        }

        return view('attendances.employee', compact('attendances'));
    }
}
