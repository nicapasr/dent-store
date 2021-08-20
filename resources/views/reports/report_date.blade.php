@extends('layouts.master')

@section('title', 'รายงานวัสดุตามวันที่กำหนด')
@section('title_page', 'รายงานวัสดุตามวันที่กำหนด')

@section('content')
{{-- นำเข้า --}}
{{-- {!! Form::open(['route' => 'reports.by.date', 'method' => 'get']) !!} --}}
<div class="row d-flex justify-content-center align-items-end mb-4">
    <div class="col-12 col-sm-4">
        {!! Form::label('from', 'เริ่ม') !!}
        {!! Form::datetime('from', 'dd-mm-yyyy', ['class' => 'form-control datepicker text-center']) !!}
    </div>
    <div class="col-12 col-sm-4">
        {!! Form::label('to', 'ถึง') !!}
        {!! Form::datetime('to', 'dd-mm-yyyy', ['class' => 'form-control datepicker text-center']) !!}
    </div>
    <div class="col-12 col-sm-auto d-flex justify-content-center mt-3 mt-sm-0">
        {!! Form::submit('ยืนยัน', ['id' => 'btn_submit', 'class' => 'btn btn-success']) !!}
    </div>
</div>
{{-- {!! Form::close() !!} --}}

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h5 class="h3 mb-0">กราฟนำเข้าวัสดุ</h5>
            </div>
            {!! Form::open(['route' => 'reports.download.excelStockInDate', 'id' => 'download_excel_stock_in']) !!}
            <div class="col-auto">
                <input type="hidden" name="excel_from" id="label_excel_from" value="{{old('excel_from')}}">
                <input type="hidden" name="excel_to" id="label_excel_to" value="{{old('excel_to')}}">
                {!! Form::submit('ดาวน์โหลด Excel', ['id' => 'btn_download_excel_in', 'class' => 'btn btn-success']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="chart_stock_in" class="chart-canvas"></canvas>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h5 class="h3 mb-0">กราฟเบิกวัสดุ</h5>
            </div>
            {!! Form::open(['route' => 'reports.download.excelStockOutDate', 'id' => 'download_excel_stock_out']) !!}
            <div class="col-auto">
                <input type="hidden" name="from_excel" id="label_excel_from_out" value="{{old('from_excel')}}">
                <input type="hidden" name="to_excel" id="label_excel_to_out" value="{{old('to_excel')}}">
                {!! Form::submit('ดาวน์โหลด Excel', ['id' => 'btn_download_excel_out', 'class' => 'btn btn-success'])!!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="card-body">
        <div class="chart">
            <canvas id="chart_stock_out" class="chart-canvas"></canvas>
        </div>
    </div>
</div>

<script type="application/javascript"
    src="{{ asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script type="application/javascript" src="{{asset('assets/js/myScript/reports/report_date.js')}}"></script>
@endsection
