@extends('layouts.app')
@section('title', isset($attendance) ? 'Cập nhật chấm công' : 'Thêm chấm công')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    {{ isset($attendance) ? 'Cập nhật bản ghi chấm công' : 'Thêm bản ghi chấm công' }}
                </h3>
            </div>

            <div class="card-body">
                <form method="POST"
                    action="{{ isset($attendance) ? route('attendances.update', $attendance->id) : route('attendances.store') }}">
                    @csrf
                    @if(isset($attendance)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nhân viên</label>
                            <select name="user_id" class="form-control" {{ isset($attendance) ? 'disabled' : '' }} required>
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ (isset($attendance) && $attendance->user_id == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Ngày làm việc</label>
                            <input type="date" name="work_date" class="form-control"
                                value="{{ $attendance->work_date ?? old('work_date') }}" required
                                {{ isset($attendance) ? 'readonly' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Giờ vào</label>
                            <input type="time" name="check_in" class="form-control"
                                value="{{ $attendance->check_in ?? old('check_in') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Giờ ra</label>
                            <input type="time" name="check_out" class="form-control"
                                value="{{ $attendance->check_out ?? old('check_out') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control" required>
                            @foreach(['Present','Absent','Late','Leave'] as $st)
                                <option value="{{ $st }}" {{ (isset($attendance) && $attendance->status == $st) ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3">{{ $attendance->note ?? old('note') }}</textarea>
                    </div>

                    <div class="d-flex">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fas fa-save"></i> {{ isset($attendance) ? 'Cập nhật' : 'Lưu thông tin' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
