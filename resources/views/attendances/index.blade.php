@extends('layouts.app')
@section('title', 'Quản lý chấm công')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-2"></i>Danh sách chấm công</h3>
            </div>
            <div class="card-body">
                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('attendances.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                            placeholder="Tìm theo tên nhân viên">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">-- Trạng thái --</option>
                            @foreach(['Present','Absent','Late','Leave'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Lọc</button>
                    </div>
                </form>

                <div class="mb-3">
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm chấm công
                    </a>
                </div>

                {{-- Bảng dữ liệu --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-primary">
                            <tr>
                                <th>#</th>
                                <th>Nhân viên</th>
                                <th>Ngày làm</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->work_date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->check_in ?? '-' }}</td>
                                    <td>{{ $item->check_out ?? '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $item->status == 'Present' ? 'bg-success' :
                                               ($item->status == 'Late' ? 'bg-warning' :
                                               ($item->status == 'Leave' ? 'bg-info' : 'bg-danger')) }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>{{ $item->note ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('attendances.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('attendances.destroy', $item->id) }}" method="POST"
                                            class="d-inline-block" onsubmit="return confirm('Xóa bản ghi này?');">
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
                <div class="d-flex justify-content-center mt-3">
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
