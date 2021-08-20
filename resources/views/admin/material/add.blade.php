@extends('layouts.master')

@section('title', 'เพิ่มวัสดุใหม่')
@section('title_page', 'เพิ่มวัสดุใหม่')

@section('content')

<div class="d-flex justify-content-center">
    <div class="col-sm-8 col-md-8">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">ข้อมูลการเพิ่มวัสดุใหม่</h3>
                    </div>
                    {{-- <div class="col text-right">
                            <a href="#!" class="btn btn-sm btn-primary">See all</a>
                        </div> --}}
                </div>
            </div>
            <form action="{{route('admin.material.new.add')}}" method="POST">
                @csrf
                <div class="container px-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('warehouses', 'นำเข้าจาก:') !!}
                                <select id="warehouses" class="form-control @error('m_warehouses') is-invalid @enderror"
                                    name="m_warehouses" onchange="clearErrors('warehouses');">

                                    {{-- <option value="" disabled selected>เลือกคลังที่เบิกมา</option> --}}
                                    @foreach ($wareHouses as $wareHouse)
                                    <option value="{{ $wareHouse->id_warehouse }}"
                                        {{ old('m_warehouses') == $wareHouse->id_warehouse ? 'selected' : '' }}
                                        {{ $wareHouse->id_warehouse == 1 && !old('m_warehouses') ? 'selected' : '' }}>
                                        {{ $wareHouse->warehouse_name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('m_warehouses')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::checkbox('checkbox_exp', 'true', false, ['id' => 'checkbox_m_exp_date']) !!}
                                {!! Form::label('m_exp_date', 'วันหมดอายุ:') !!}
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span id="ic_calendar"
                                            class="input-group-text @error('m_exp_date') border-danger @enderror"><i
                                                class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    {!! Form::datetime('m_exp_date', null, [
                                    'id' => 'input_m_exp_date',
                                    'class' => 'form-control datepicker '. ($errors->has('m_exp_date') ? 'is-invalid' : null),
                                    'autocomplete' => 'off',
                                    'placeholder' => 'เลือกวันที่นำเข้า',
                                    'onchange' => 'clearErrors("input_m_exp_date")
                                    $("#ic_calendar").removeClass("border-danger")',
                                    'disabled' => (old('checkbox_exp') == 'true' ? false : true)])
                                    !!}

                                    @error('m_exp_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('m_code', 'รหัสวัสดุ:') !!}
                                {!! Form::text('m_code', old('m_code'), ['class' => 'form-control ' .
                                ($errors->has('m_code') ? 'is-invalid' : null), 'onkeyup' => 'clearErrors("m_code")'])
                                !!}

                                @error('m_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="m_name">ชื่อวัสดุ:</label>
                                <input id="m_name" type="text"
                                    class="form-control @error('m_name') is-invalid @enderror" name="m_name"
                                    value="{{ old('m_name') }}" onkeydown="clearErrors('m_name');">

                                @error('m_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="unit">หน่วยนับ:</label>
                                <select id="unit" class="form-control @error('m_unit') is-invalid @enderror"
                                    name="m_unit" onchange="clearErrors('unit');">
                                    <option value="" disabled selected>เลือกหน่วยนับ</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id_unit }}"
                                        {{ old('m_unit') == $unit->id_unit ? 'selected' : '' }}>
                                        {{ $unit->unit_name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('m_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_value_in">จำนวนนำเข้า:</label>
                                <input id="input_value_in" type="number"
                                    class="form-control @error('m_in') is-invalid @enderror" name="m_in" min="0"
                                    value="{{ old('m_in') }}" onkeyup="clearErrors('input_value_in');">

                                @error('m_in')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group"> --}}
                                {{-- {!! Form::hidden('m_type', 1) !!} --}}
                                {{-- <label for="type">ประเภท:</label> --}}
                                {{-- <select id="type" class="form-control d-none @error('m_type') is-invalid @enderror"
                                    name="m_type" onchange="clearErrors('type');">
                                    <option value="" disabled selected>เลือกประเภท</option>
                                    @foreach ($materialTypes as $materialType)
                                    <option value="{{ $materialType->id_type }}"
                                        {{ old('m_type') == $materialType->id_type ? 'selected' : '' }}>
                                        {{ $materialType->type_name }}
                                    </option>
                                    @endforeach
                                </select> --}}

                                {{-- @error('m_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror --}}
                            {{-- </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_price_unit">ราคาต่อหน่วย:</label>
                                <input id="input_price_unit" type="number"
                                    class="form-control @error('m_price_unit') is-invalid @enderror" name="m_price_unit"
                                    min="0" value="{{ old('m_price_unit') }}"
                                    onkeyup="clearErrors('input_price_unit'); calTotalPrice();">

                                @error('m_price_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div> --}}

                    </div>
                    {{-- <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_total_price">ราคาทั้งหมด:</label>
                                <input id="input_total_price" type="number" class="form-control" name="total_price"
                                    value="{{ old('input_total_price') }}" onkeyup="clearErrors('input_total_price');"
                                    readonly>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="text-center py-4">
                    <button id="btSubmit" type="submit" class="btn btn-success">เพิ่มวัสดุ</button>
                    <button type="reset" class="btn btn-danger">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript" src="{{asset('assets/js/myScript/materials/new_material.js')}}"></script>
<script type="application/javascript"
    src="{{asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
@endsection
