@extends('layouts.app')
@section('title', 'Quản lý bảng lương')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-money-bill-wave me-2"></i>Danh sách bảng lương</h3>
            </div>

            <div class="card-body">
                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('salaries.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                            placeholder="Tìm theo tên nhân viên">
                    </div>
                    <div class="col-md-3">
                        <select name="month" class="form-control">
                            <option value="">-- Tháng --</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="year" class="form-control">
                            <option value="">-- Năm --</option>
                            @for($y=date('Y'); $y>=2020; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Lọc</button>
                    </div>
                </form>

                <div class="mb-3">
                    <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm bảng lương
                    </a>
                </div>

                {{-- Bảng dữ liệu --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-primary">
                            <tr>
                                <th>#</th>
                                <th>Nhân viên</th>
                                <th>Tháng/Năm</th>
                                <th>Ngày công</th>
                                <th>Lương cơ bản (VNĐ)</th>
                                <th>Thưởng (VNĐ)</th>
                                <th>Khấu trừ (VNĐ)</th>
                                <th>Tổng lương (VNĐ)</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salaries as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($salaries->currentPage() - 1) * $salaries->perPage() }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->month }}/{{ $item->year }}</td>
                                    <td>{{ $item->total_days }}</td>
                                    <td>{{ number_format($item->base_salary, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->bonus ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->deduction ?? 0, 0, ',', '.') }}</td>
                                    <td class="fw-bold text-success">
                                        {{ number_format(($item->base_salary + ($item->bonus ?? 0) - ($item->deduction ?? 0)), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('salaries.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('salaries.destroy', $item->id) }}" method="POST"
                                              class="d-inline-block" onsubmit="return confirm('Xóa bảng lương này?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-muted">Không có dữ liệu</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div>
                    {{ $salaries->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
