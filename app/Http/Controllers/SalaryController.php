<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\User;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
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
        $query = Salary::with('user');

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $salaries = $query->orderBy('year', 'desc')
                          ->orderBy('month', 'desc')
                          ->paginate(10);

        return view('salaries.index', compact('salaries'));
    }

    public function create(Request $request)
    {
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $users = User::withCount(['attendances as total_attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('work_date', $month)
                ->whereYear('work_date', $year);
        }])
        ->orderBy('name')
        ->get();

        return view('salaries.form', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'month'       => 'required|integer|between:1,12',
            'year'        => 'required|numeric',
            'total_days'  => 'required|integer|min:0|max:31',
            'base_salary' => 'required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
        ]);

        $exists = Salary::where('user_id', $validated['user_id'])
                        ->where('month', $validated['month'])
                        ->where('year', $validated['year'])
                        ->exists();

        if ($exists) {
            $this->logAction('Tính lương thất bại (trùng dữ liệu)', $request);
            return back()->with('error', 'Bảng lương cho nhân viên này đã tồn tại trong tháng và năm được chọn.');
        }

        $validated['total_salary'] = $validated['base_salary'] + ($validated['bonus'] ?? 0) - ($validated['deduction'] ?? 0);

        Salary::create($validated);

        $this->logAction('Tính lương nhân viên #' . $validated['user_id'], $request);

        return redirect()->route('salaries.index')->with('success', 'Thêm bảng lương thành công.');
    }

    public function edit($id, Request $request)
    {
        $salary = Salary::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('salaries.form', compact('salary', 'users'));
    }

    public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $validated = $request->validate([
            'total_days'  => 'required|integer|min:0|max:31',
            'base_salary' => 'required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
        ]);

        $validated['total_salary'] = $validated['base_salary'] + ($validated['bonus'] ?? 0) - ($validated['deduction'] ?? 0);

        $salary->update($validated);

        $this->logAction("Cập nhật bảng lương #$id", $request);

        return redirect()->route('salaries.index')->with('success', 'Cập nhật bảng lương thành công.');
    }

    public function destroy($id, Request $request)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        $this->logAction("Xóa bảng lương #$id", $request);

        return redirect()->route('salaries.index')->with('success', 'Đã xóa bảng lương.');
    }
}
