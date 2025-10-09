<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLog;

class SalaryEmployeeController extends Controller
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
        $query = $user->salaries()->orderBy('month', 'desc');

        $logDetails = [];

        if ($request->filled('month')) {
            $query->where('month', $request->month);
            $logDetails[] = "Tháng: {$request->month}";
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
            $logDetails[] = "Năm: {$request->year}";
        }

        $salaries = $query->paginate(10);

        if (!empty($logDetails)) {
            $detailText = implode(', ', $logDetails);
            $this->logAction("Tìm kiếm bảng lương cá nhân ({$detailText})", $request);
        }

        return view('salaries.employee', compact('salaries'));
    }
}
