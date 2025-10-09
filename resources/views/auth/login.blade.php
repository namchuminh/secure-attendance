<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-header text-center">
                <h4><b>ĐĂNG NHẬP</b></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <label for="email">Email</label>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <label for="password">Mật khẩu</label>
                    <div class="input-group mb-3">
                        
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember"> Ghi nhớ đăng nhập?
                            </div>
                        </div>
                    </div>
                    <div class="w-100">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
</html>