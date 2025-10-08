@extends('layouts.app')
@section('title', isset($salary) ? 'Cập nhật bảng lương' : 'Thêm bảng lương')

@section('content')
<section class="content">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title">
                    <i class="fas fa-money-bill me-2"></i>
                    {{ isset($salary) ? 'Cập nhật bảng lương' : 'Thêm bảng lương mới' }}
                </h3>
            </div>

            <div class="card-body">
                <form method="POST"
                    action="{{ isset($salary) ? route('salaries.update', $salary->id) : route('salaries.store') }}">
                    @csrf
                    @if(isset($salary)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nhân viên</label>
                            <select id="user_id" name="user_id" class="form-control" {{ isset($salary) ? 'disabled' : '' }} required>
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                            data-base-salary="{{ $user->base_salary ?? 0 }}"
                                            data-total-days="{{ $user->total_attendances ?? 0 }}"
                                            {{ (isset($salary) && $salary->user_id == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Tháng</label>
                            <input type="number" name="month2" min="1" max="12" class="form-control bg-light"
                                value="{{ old('month', $salary->month ?? date('n')) }}"
                                {{ isset($salary) ? 'readonly' : '' }} required disabled>
                            <input type="hidden" name="month" min="1" max="12" class="form-control"
                                value="{{ old('month', $salary->month ?? date('n')) }}"
                                {{ isset($salary) ? 'readonly' : '' }} required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Năm</label>
                            <input type="number" name="year2" min="2020" class="form-control bg-light"
                                   value="{{ old('year', $salary->year ?? date('Y')) }}" 
                                   {{ isset($salary) ? 'readonly' : '' }} required disabled>
                            <input type="hidden" name="year" min="2020" class="form-control"
                                   value="{{ old('year', $salary->year ?? date('Y')) }}"
                                   {{ isset($salary) ? 'readonly' : '' }} required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Ngày công</label>
                            <input type="number" name="total_days2" class="form-control bg-light"
                                   value="{{ old('total_days', $salary->total_days ?? 0) }}" required disabled>
                            <input type="hidden" name="total_days" class="form-control"
                                   value="{{ old('total_days', $salary->total_days ?? 0) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Lương cơ bản (VNĐ)</label>
                            <input type="text" id="base_salary2" name="base_salary2" step="1000" class="form-control bg-light"
                                   value="{{ old('base_salary', number_format($salary->base_salary ?? 0, 0, '', '')) }}" required readonly disabled>
                            <input type="hidden" id="base_salary" name="base_salary" step="1000" class="form-control"
                                   value="{{ old('base_salary', $salary->base_salary ?? 0) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Thưởng (VNĐ)</label>
                            <input type="number" id="bonus" name="bonus" step="1000" class="form-control"
                                   value="{{ old('bonus', number_format($salary->bonus ?? 0, 0, '', '')) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Khấu trừ (VNĐ)</label>
                            <input type="number" id="deduction" name="deduction" step="1000" class="form-control"
                                   value="{{ old('deduction', number_format($salary->deduction ?? 0, 0, '', '')) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tổng lương thực nhận (VNĐ)</label>
                        <input type="text" id="total_salary" class="form-control bg-light fw-bold text-success"
                               readonly value="0">
                    </div>

                    <div class="d-flex">
                        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fas fa-save"></i> {{ isset($salary) ? 'Cập nhật' : 'Lưu mới' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .form-control:disabled, .form-control[readonly] {
        background-color: white;
        opacity: 1;
        cursor: not-allowed;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function calcTotal() {
        const base = parseFloat($('#base_salary').val()) || 0;
        const bonus = parseFloat($('#bonus').val()) || 0;
        const deduct = parseFloat($('#deduction').val()) || 0;
        $('#total_salary').val((base + bonus - deduct).toLocaleString('vi-VN'));
    }

    // Khi chọn nhân viên → lấy base_salary từ option
    $('#user_id').on('change', function() {
        const baseSalary = $(this).find(':selected').data('base-salary') || 0;
        $('#base_salary').val(baseSalary);
        let displaySalary = new Intl.NumberFormat('vi-VN').format(baseSalary);
        $('#base_salary2').val(displaySalary);

        const selected = $(this).find(':selected');
        const totalDays = selected.data('total-days') || 0;
        $('input[name="total_days"]').val(totalDays);
        let displayDays = new Intl.NumberFormat('vi-VN').format(totalDays);
        $('input[name="total_days2"]').val(displayDays);

        calcTotal();
    });

    // Cập nhật tổng realtime
    $('#base_salary, #bonus, #deduction').on('input', calcTotal);
    calcTotal();
</script>
@endsection
