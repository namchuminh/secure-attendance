<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessLog;

class LoginController extends Controller
{
    protected function logAction($userId, $action, Request $request)
    {
        AccessLog::create([
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $this->logAction($user->id, 'Đăng nhập thành công', $request);

            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công');
        }

        // Ghi log đăng nhập thất bại (không có user_id thật)
        $this->logAction(null, "Đăng nhập thất bại (email: {$request->email})", $request);

        return back()->withErrors(['error' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->logAction($user->id, 'Đăng xuất', $request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất');
    }
}
