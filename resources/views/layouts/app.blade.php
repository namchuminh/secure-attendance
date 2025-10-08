<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Hệ thống chấm công')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('dist/img/avatar.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link text-dark">
                    <i class="fas fa-right-from-bracket"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
            <img src="{{ asset('dist/img/avatar5.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight-light">HỆ THỐNG QUẢN LÝ</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    {{-- Trang chủ --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Trang chủ</p>
                        </a>
                    </li>

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-header">QUẢN LÝ NHÂN SỰ</li>
                    @else
                        <li class="nav-header">QUẢN LÝ CHẤM CÔNG</li>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Danh sách nhân viên</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Chấm công & điểm danh</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('salaries.index') }}" class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>Bảng lương nhân viên</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('access-logs.index') }}" class="nav-link {{ request()->routeIs('access-logs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shield-halved"></i>
                                <p>Nhật ký hệ thống</p>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('employee.attendances') }}" class="nav-link {{ request()->routeIs('employee.attendances*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Chấm công hàng ngày</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('employee.salaries') }}" class="nav-link {{ request()->routeIs('employee.salaries*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>Lương theo tháng</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-header">TÀI KHOẢN & CẤU HÌNH</li>
                    <li class="nav-item">
                        <a href="{{ route('profiles.index') }}" class="nav-link {{ request()->routeIs('profiles.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-gear"></i>
                            <p>Thông tin cá nhân</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Cấu hình hệ thống</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Nội dung chính -->
    <div class="content-wrapper">
        @yield('content')
    </div>

    <footer class="main-footer">
        <strong>&copy; 2024–2025 | Hệ thống chấm công – tính lương.</strong>
        <div class="float-right d-none d-sm-inline-block">
            <b>Phiên bản</b> 1.0.0
        </div>
    </footer>

</div>

<!-- JS -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

{{-- Toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

@yield('script')

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            $(function () { toastr.error('{{ $error }}', 'Thất bại'); });
        </script>
    @endforeach
@endif

@if (session('success'))
    <script>$(function () { toastr.success('{{ session('success') }}', 'Thành công'); });</script>
@endif

@if (session('error'))
    <script>$(function () { toastr.error('{{ session('error') }}', 'Thất bại'); });</script>
@endif

</body>
</html>
