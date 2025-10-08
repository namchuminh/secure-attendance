@extends('layouts.app')
@section('title', 'Trang chủ hệ thống')

@section('content')
<section class="content">
    <div class="container-fluid mt-4">

        {{-- Hàng thống kê nhanh --}}
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalUsers }}</h3>
                        <p>Tổng số nhân viên</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('users.index') }}" class="small-box-footer">
                        Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $todayAttendance }}</h3>
                        <p>Đã chấm công hôm nay</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="{{ route('attendances.index') }}" class="small-box-footer">
                        Xem danh sách <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($monthSalary, 0, ',', '.') }} ₫</h3>
                        <p>Tổng lương tháng {{ \Carbon\Carbon::now()->month }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <a href="{{ route('salaries.index') }}" class="small-box-footer">
                        Xem bảng lương <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Hàng 2: Biểu đồ & log --}}
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Thống kê tổng quan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartOverview" height="130"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title"><i class="fas fa-bell"></i> Hoạt động gần đây</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fas fa-user-plus text-success mr-2"></i>Nhân viên mới được thêm</li>
                            <li class="list-group-item"><i class="fas fa-calendar-check text-primary mr-2"></i>Cập nhật chấm công hôm nay</li>
                            <li class="list-group-item"><i class="fas fa-coins text-warning mr-2"></i>Tính lương tháng này hoàn tất</li>
                        </ul>
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
    const ctx = document.getElementById('chartOverview').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'],
            datasets: [{
                label: 'Tổng lương (VNĐ)',
                data: [12000000, 14000000, 11000000, 15000000, 17000000, 16500000, 15500000, 17500000, 16000000, 18000000, 19000000, 20000000],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
