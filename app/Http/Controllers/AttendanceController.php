<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Ghi log hành động
    protected function logAction($action, Request $request)
    {
        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    // Hiển thị danh sách
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('work_date', $request->date);
        }

        $attendances = $query->orderBy('work_date', 'desc')->paginate(10);

        return view('attendances.index', compact('attendances'));
    }

    public function create(Request $request)
    {
        $users = User::orderBy('name')->get();

        return view('attendances.form', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:Present,Absent,Late,Leave',
            'note' => 'nullable|string|max:255',
        ]);

        Attendance::create($validated);

        $this->logAction('Chấm công nhân viên #' . $validated['user_id'], $request);

        return redirect()->route('attendances.index')->with('success', 'Thêm chấm công thành công.');
    }

    public function edit($id, Request $request)
    {
        $attendance = Attendance::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('attendances.form', compact('attendance', 'users'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:Present,Absent,Late,Leave',
            'note' => 'nullable|string|max:255',
        ]);

        $attendance->update($validated);

        $this->logAction("Cập nhật chấm công #$id", $request);

        return redirect()->route('attendances.index')->with('success', 'Cập nhật chấm công thành công.');
    }

    public function destroy($id, Request $request)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        $this->logAction("Xóa chấm công #$id", $request);

        return redirect()->route('attendances.index')->with('success', 'Đã xóa bản ghi chấm công.');
    }
}
