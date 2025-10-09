<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AccessLog;

class ProfileController extends Controller
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
        $user = auth()->user();

        return view('profiles.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            $validated['password'] = $user->password;
        }

        $user->update($validated);

        $this->logAction('Cập nhật thông tin cá nhân', $request);

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công');
    }
}
