@extends('layouts.app')
@section('title', 'Bảng điều khiển nhân viên')

@section('content')
<section class="content">
    <div class="container-fluid mt-4">

        {{-- Hàng thống kê nhanh --}}
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $attendanceCount }}</h3>
                        <p>Số ngày công tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="{{ route('employee.attendances') }}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($currentSalary, 0, ',', '.') }} ₫</h3>
                        <p>Lương tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <a href="{{ route('employee.salaries') }}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $lateDays }}</h3>
                        <p>Số ngày đi trễ tháng {{ $month }}/{{ $year }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('employee.attendances') }}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Biểu đồ thống kê --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Tổng lương theo tháng</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="salaryChart" height="130"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Số ngày đi làm theo tháng</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'];

    // Biểu đồ lương
    new Chart(document.getElementById('salaryChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Tổng lương (VNĐ)',
                data: @json($monthlySalaries),
                backgroundColor: '#453ad8ff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }},
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => v.toLocaleString('vi-VN')
                    }
                }
            }
        }
    });

    // Biểu đồ ngày công
    new Chart(document.getElementById('attendanceChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Số ngày đi làm',
                data: @json($monthlyAttendance),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }},
            scales: { y: { beginAtZero: true }}
        }
    });
</script>
@endsection
