<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // Giả sử bạn có mô hình Salary và quan hệ với User
        $salaries = $user->salaries()->orderBy('month', 'desc')->paginate(10);

        return view('salaries.employee', compact('salaries'));
    }
}
