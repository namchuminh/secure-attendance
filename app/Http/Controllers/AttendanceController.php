<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Hiển thị danh sách chấm công (có tìm kiếm, lọc, phân trang)
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        // Tìm kiếm theo tên nhân viên
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

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

        return view('attendances.index', compact('attendances'));
    }

    // Form thêm mới
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('attendances.form', compact('users'));
    }

    // Lưu chấm công mới
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

        return redirect()->route('attendances.index')->with('success', 'Thêm chấm công thành công.');
    }

    // Form sửa
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('attendances.form', compact('attendance', 'users'));
    }

    // Cập nhật bản ghi
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

        return redirect()->route('attendances.index')->with('success', 'Cập nhật chấm công thành công.');
    }

    // Xóa bản ghi
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Đã xóa bản ghi chấm công.');
    }
}
