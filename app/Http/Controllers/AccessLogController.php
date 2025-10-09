<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessLog::with('user');

        // Tìm theo tên người dùng
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('access_logs.index', compact('logs'));
    }
}
