@extends('layouts.app')
@section('title', isset($user) ? 'Cập nhật nhân viên' : 'Thêm nhân viên')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-user-edit me-2"></i>
                    {{ isset($user) ? 'Cập nhật thông tin nhân viên' : 'Thêm nhân viên mới' }}
                </h3>
            </div>

            <div class="card-body">
                <form method="POST" 
                      action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                    @csrf
                    @if(isset($user)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Họ và tên</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="{{ isset($user) ? 'Để trống nếu không đổi' : 'Nhập mật khẩu' }}"
                                   {{ isset($user) ? '' : 'required' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Vai trò</label>
                            <select name="role" class="form-control" required>
                                <option value="employee" {{ (old('role', $user->role ?? '') == 'employee') ? 'selected' : '' }}>Nhân viên</option>
                                <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Quản trị</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Ngày vào làm</label>
                            <input type="date" name="hire_date" class="form-control"
                                   value="{{ old('hire_date', $user->hire_date ?? '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Lương cơ bản (VNĐ)</label>
                            <input type="number" name="base_salary" class="form-control"
                                   value="{{ old('base_salary', $user->base_salary ?? 0) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ (old('status', $user->status ?? 1) == 1) ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ (old('status', $user->status ?? 1) == 0) ? 'selected' : '' }}>Ngừng</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fas fa-save"></i> {{ isset($user) ? 'Cập nhật' : 'Lưu mới' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
