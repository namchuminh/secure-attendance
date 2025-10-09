@extends('layouts.app')
@section('title', 'Quản lý nhân viên')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-users me-2"></i>Danh sách nhân viên</h3>
            </div>

            <div class="card-body">
                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Tìm theo tên hoặc email">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-control">
                            <option value="">-- Vai trò --</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị</option>
                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Nhân viên</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ngừng</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Lọc</button>
                    </div>
                </form>

                <div class="mb-3">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Thêm nhân viên
                    </a>
                </div>

                {{-- Bảng dữ liệu --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-primary">
                            <tr>
                                <th>#</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>SĐT</th>
                                <th>Ngày vào làm</th>
                                <th>Lương cơ bản (VNĐ)</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-info text-dark' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>{{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ number_format($user->base_salary, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $user->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $user->status ? 'Hoạt động' : 'Ngừng' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline-block" onsubmit="return confirm('Xóa nhân viên này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-muted">Không có dữ liệu</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
