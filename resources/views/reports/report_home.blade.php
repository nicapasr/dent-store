@extends('layouts.master')

@section('title', 'เลือกรายงาน')
@section('title_page', 'เลือกรายงาน')

@section('content')
<div class="d-flex justify-content-center align-items-center">
    <div class="card w-50">
        <div class="card-body">
            {!! Form::open(['route' => 'reports.show', 'method' => 'get']) !!}
                <select class="form-control @error('report') is-invalid
                                                @enderror" name="report">
                    <option value="" disabled selected>เลือกรายงาน</option>
                    <option value="date">รายงานวัสดุตามวันที่กำหนด</option>
                    <option value="warehouse">รายงานแยกตามคลังที่เบิกมา</option>
                    {{-- <option value="type">รายงานแยกตามประเภทวัสดุ</option> --}}
                    {{-- <option value="year">รายงานวัสดุรายปี</option> --}}
                    {{-- <option value="compare_price">รายงานเปรียบเทียบราคารวมประจำปี</option> --}}
                    {{-- <option value="day">รายงานเปรียบเทียบการนำเข้าต่อการเบิก</option> --}}
                    {{-- <option value="day">รายงานเปรียบเทียบราคารวมประจำปี</option> --}}
                </select>

                @error('report')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="d-flex justify-content-center mt-4">
                    <button id="btn_report_submit" type="submit" class="btn btn-success">เลือก</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
