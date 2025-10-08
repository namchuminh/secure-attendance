<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    private $path = 'config/custom_settings.json';

    public function index()
    {
        $settings = [];
        if (File::exists(base_path($this->path))) {
            $settings = json_decode(File::get(base_path($this->path)), true);
        }

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'work_start'   => 'required',
            'work_end'     => 'required',
        ]);

        File::put(base_path($this->path), json_encode($data, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Đã lưu cấu hình hệ thống.');
    }
}
