@extends('layouts.app')
@section('title', 'Thông tin cá nhân')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card card-primary">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-user-edit me-2"></i>Thông tin cá nhân</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('profiles.update') }}">
                    @csrf
                    @method('POST')

                        <div class="mb-3">
                            <label>Họ và tên</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>

                    <hr>

                    <h5 class="mb-3">Đổi mật khẩu</h5>
                    <div class="mb-3">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới">
                    </div>
                    <div class="mb-3">
                        <label>Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    // Hiện/ẩn mật khẩu
    $('#togglePassword').on('click', function() {
        const pass = $('#password');
        const confirm = $('#password_confirmation');
        const type = pass.attr('type') === 'password' ? 'text' : 'password';
        pass.attr('type', type);
        confirm.attr('type', type);
    });

    // Kiểm tra khớp mật khẩu (client-side)
    $('form').on('submit', function(e) {
        const pass = $('#password').val();
        const confirm = $('#password_confirmation').val();
        if (pass && pass !== confirm) {
            e.preventDefault();
            toastr.error('Mật khẩu nhập lại không khớp', 'Thất bại');
        }
    });
</script>
@endsection
