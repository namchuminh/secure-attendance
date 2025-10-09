<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
            'hire_date' => 'nullable|date',
            'role' => 'required|in:admin,employee',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        $this->logAction('Thêm nhân viên mới', $request);

        return redirect()->route('users.index')->with('success', 'Thêm nhân viên thành công');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.form', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'phone' => 'nullable|string|max:15',
            'hire_date' => 'nullable|date',
            'role' => 'required|in:admin,employee',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            $validated['password'] = $user->password;
        }

        $user->update($validated);

        $this->logAction("Cập nhật thông tin nhân viên #$id", $request);

        return redirect()->route('users.index')->with('success', 'Cập nhật nhân viên thành công');
    }

    public function destroy($id, Request $request)
    {
        User::findOrFail($id)->delete();

        $this->logAction("Xóa nhân viên #$id", $request);

        return redirect()->route('users.index')->with('success', 'Đã xóa nhân viên');
    }
}
