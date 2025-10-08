<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'hire_date',
        'status',
        'base_salary'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Quan hệ: 1 user có nhiều bản chấm công
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Quan hệ: 1 user có nhiều bảng lương
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    // Quan hệ: 1 user có nhiều log truy cập
    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    // Kiểm tra quyền
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
