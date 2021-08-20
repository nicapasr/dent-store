@extends('layouts.master')

@section('title', 'รายงานแยกตามคลังที่เบิกมา')
@section('title_page', 'รายงานแยกตามคลังที่เบิกมา')

@section('content')
{{-- นำเข้า --}}
<div class="row d-flex justify-content-center align-items-end mb-4">
    <div class="col-6 col-sm-4">
        <h3 class="mb-0">เลือกคลังที่เบิกมา</h3>
        <select id="select_warehouse" class="form-control m-0">
            <option value="" disabled selected>คลัง</option>
            @foreach ($warehouses as $item)
            <option value="{{$item->id_warehouse}}">{{$item->warehouse_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-sm-4">
        <h3 class="mb-0">เลือกปี</h3>
        <select id="select_year" class="form-control m-0">
            {{ $y = date("Y") }}
            <option value="" disabled selected>ปี</option>
            @for ($i = 0; $i < 5; $i++) <option value="{{ (int)$y - $i }}">
                {{ (int)$y - $i }}</option>
                @endfor
        </select>
    </div>
    <div class="col-12 col-sm-auto d-flex justify-content-center mt-3 mt-sm-0">
        <button id="btn_submit" type="submit" class="btn btn-success">ยืนยัน</button>
    </div>
</div>
<div id="div_grap" class="d-none">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5 class="h3 mb-0">กราฟนำเข้าวัสดุ</h5>
                </div>
                {!! Form::open(['route' => 'reports.download.excelStockInWarehouse', 'id' => 'download_excel_stock_in']) !!}
                <div class="col-auto">
                    {{-- <select id="stock_in_order" class="form-control m-0">
                        <option value="" disabled selected>การเรียง</option>
                        <option value="desc">มากไปน้อย</option>
                        <option value="asc">น้อยไปมาก</option>
                    </select> --}}
                    <input type="hidden" name="stock_in_warehouse_excel" id="label_excel_warehouse" value="{{old('warehouse_excel')}}">
                    <input type="hidden" name="stock_in_year_excel" id="label_excel_year" value="{{old('year_excel')}}">
                    {!! Form::submit('ดาวน์โหลด Excel', ['id' => 'btn_download_excel_in', 'class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="chart_stock_in" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- เบิก --}}
    {{-- <div class="d-flex justify-content-center align-items-center mb-4">
    <h3 class="mb-0">เลือกปี</h3>
    <div class="col-2">
        <select id="stock_out_year" class="form-control m-0">
            {{ $y = date("Y") }}
    @for ($i = 0; $i < 5; $i++) <option value="{{ (int)$y - $i }}" @if($i==0) selected @endif>
        {{ (int)$y - $i }}</option>
        @endfor
        </select>
</div>
</div> --}}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h5 class="h3 mb-0">กราฟเบิกวัสดุ</h5>
            </div>
            {!! Form::open(['route' => 'reports.download.excelStockOutWarehouse', 'id' => 'download_excel_stock_out']) !!}
            <div class="col-auto">
                {{-- <select id="stock_out_order" class="form-control w-auto float-right m-0">
                    <option value="" disabled selected>การเรียง</option>
                    <option value="desc">มากไปน้อย</option>
                    <option value="asc">น้อยไปมาก</option>
                </select> --}}
                <input type="hidden" name="stock_out_warehouse_excel" id="label_excel_warehouse_out" value="{{old('warehouse_excel')}}">
                <input type="hidden" name="stock_out_year_excel" id="label_excel_year_out" value="{{old('year_excel')}}">
                {!! Form::submit('ดาวน์โหลด Excel', ['id' => 'btn_download_excel_out', 'class' => 'btn btn-success']) !!}
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
</div>
<script type="application/javascript" src="{{asset('assets/js/myScript/reports/report_warehouse.js')}}"></script>
@endsection
