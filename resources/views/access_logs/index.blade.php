@extends('layouts.app')
@section('title', 'Nhật ký hệ thống')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title"><i class="fas fa-clipboard-list mr-2"></i>Nhật ký truy cập hệ thống</h3>
            </div>

            <div class="card-body">
                {{-- Bộ lọc --}}
                <form method="GET" action="{{ route('access-logs.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                            placeholder="Tìm theo tên người dùng">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control"
                            placeholder="Từ ngày">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control"
                            placeholder="Đến ngày">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Lọc
                        </button>
                    </div>
                </form>

                {{-- Bảng dữ liệu --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-primary">
                            <tr>
                                <th>#</th>
                                <th>Người dùng</th>
                                <th>Hành động</th>
                                <th>Địa chỉ IP</th>
                                <th>Trình duyệt / Thiết bị</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                                    <td>{{ $item->user->name ?? 'Không xác định' }}</td>
                                    <td class="text-start">{{ $item->action }}</td>
                                    <td>{{ $item->ip_address ?? '-' }}</td>
                                    <td class="text-break">{{ Str::limit($item->user_agent, 60) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted">Không có dữ liệu nhật ký</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                <div>
                    {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
