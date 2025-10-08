<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_days',
        'base_salary',
        'bonus',
        'deduction',
        'total_salary',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tính tổng lương tự động
    public function getTotalSalaryAttribute()
    {
        return $this->base_salary + $this->bonus - $this->deduction;
    }
}
