@extends('layouts.master')

@section('title', 'นำเข้าเพิ่มวัสดุ')
@section('title_page', 'นำเข้าวัสดุ')

@section('content')
<!-- Search form -->
<div class="d-flex justify-content-center mb-3">
    <div class="row">
        <div class="navbar-search navbar-search-dark form-inline">
            <div class="input-group mb-0">
                <div class="input-group input-group-alternative input-group-merge">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    {!! Form::text('search_material', '', [
                        'id' => 'input_search_bar',
                        'class' => 'form-control',
                        'placeholder' =>'ค้นหาวัสดุเพื่อนำเข้า',
                        'type' => 'search',
                        'autocomplete' => 'off']) !!}
                </div>
            </div>
            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
                aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
</div>
<div class="pb-0 pb-sm-2 d-flex justify-content-center" id="material_list">
    @include('search.list_material')
</div>

<div class="d-flex justify-content-center">
    <div class="col-sm-8 col-md-8">
        <div class="card
                @if (!$errors->any()) d-none @endif">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">ข้อมูลการนำเข้าวัสดุ</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.material.stock.in.update') }}" method="POST">
                @csrf
                <div class="container-fluid">
                    {{-- row 1 --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_m_code">รหัสวัสดุ:</label>
                                <input id="input_m_code" type="text" class="form-control" name="m_code"
                                    value="{{ isset($material) ? $material->m_code : old('m_code') }}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_m_name">ชื่อวัสดุ:</label>
                                <input id="input_m_name" type="text" class="form-control" name="m_name"
                                    value="{{ isset($material) ? $material->m_name : old('m_name') }}" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- row 2 --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_unit">หน่วยนับ:</label>
                                <input id="input_unit" type="text" class="form-control" name="m_unit"
                                    value="{{ isset($material) ? $material->materialUnit->unit_name : old('m_unit') }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{-- <label for="input_type">ประเภท:</label> --}}
                                <input id="input_type" type="hidden" class="form-control" name="m_type"
                                    value="{{ isset($material) ? $material->materialType->type_name : old('m_type') }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_total_material">วัสดุทั้งหมด:</label>
                                <input id="input_total_material" type="number" class="form-control" name="m_total"
                                    value="{{ isset($material) ? $material->m_total : old('m_total') }}" readonly>

                                <input type="hidden" id="total-temp" type="number"
                                    value="{{ isset($material) ? $material->m_total : old('m_total') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_balance">คงเหลือ:</label>
                                <input id="input_balance" type="number" class="form-control" name="m_balance"
                                    value="{{ isset($material) ? $material->m_balance : old('m_balance') }}" readonly>

                                <input type="hidden" id="balance-temp" type="number"
                                    value="{{ isset($material) ? $material->m_balance : old('m_balance') }}">
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="input_total_price">รวมราคา:</label>
                                <input type="number" class="form-control" id="input_total_price" name="total_price"
                                    value="{{ old('total_price') }}" readonly>
                            </div>
                        </div>
                    </div> --}}

                    <div class="container bg-dark w-100" style="height: 2px"></div>
                    <br>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::checkbox('checkbox_exp', 'true', false, ['id' => 'checkbox_date_exp']) !!}
                                {!! Form::label('input_date_exp', 'วันหมดอายุ:', ['class' => '']) !!}
                                <br>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span id="icon_date_exp"
                                            class="input-group-text @error('date_exp') border-danger @enderror"><i
                                                class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    {!! Form::datetime('date_exp', null, [
                                        'id' => 'input_date_exp',
                                        'class' => 'form-control datepicker ' . ($errors->has('date_exp') ? 'is-invalid' : null),
                                        'placeholder' => 'เลือกวันหมดอายุ',
                                        'autocomplete' => 'off',
                                        'data-date-format' => 'dd-mm-yyyy',
                                        'disabled' => $errors->has('date_exp') || old('date_exp') ? false : true,
                                        'onchange' => 'clearErrors("input_date_exp")'])!!}
                                    {{-- <input id="input_date_exp" name="date_exp"
                                        class="form-control datepicker @error('date_exp') is-invalid @enderror"
                                        placeholder="เลือกวันที่หมดอายุ" type="text" autocomplete="off"
                                        data-date-format="dd-mm-yyyy" @if (!$errors->has('date_exp')) readonly disabled
                                    @endif
                                    onchange="clearErrors('input_date_exp');"> --}}

                                    @error('date_exp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_warehouses">นำเข้าจาก:</label><br>
                                <select id="input_warehouses"
                                    class="form-control m-0 @error('m_warehouses') is-invalid @enderror"
                                    name="m_warehouses" onchange="clearErrors('input_warehouses');">

                                    {{-- <option value="" disabled selected>เลือกคลังที่เบิกมา</option> --}}
                                    @foreach ($wareHouses as $wareHouse)
                                    <option value="{{ $wareHouse->id_warehouse }}"
                                        {{ old('m_warehouses') == $wareHouse->id_warehouse ? 'selected' : '' }}
                                        {{ $wareHouse->id_warehouse == 1 ? 'selected' : '' }}>
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
                    </div>

                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_price_unit">ราคาต่อหน่วย:</label>
                                <input id="input_price_unit" type="number"
                                    class="form-control @error('m_price_unit') is-invalid @enderror" name="m_price_unit"
                                    min="0"
                                    value="{{ isset($material) ? $material->m_price_unit : old('m_price_unit') }}"
                                    onkeyup="calTotalPrice(); clearErrors('input_price_unit')">

                                @error('m_price_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="input_value_in">จำนวนนำเข้า:</label>
                                <input id="input_value_in" type="number"
                                    class="form-control @error('m_in') is-invalid @enderror" name="m_in" min="1"
                                    value="{{ old('m_in') }}" onkeyup="clearErrors('input_value_in');">

                                @error('m_in')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center pb-4">
                    <button id="btSubmit" type="submit" class="btn btn-success" style="width: 95px">เพิ่มวัสดุ</button>
                    <button type="reset" class="btn btn-danger" style="width: 95px">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript" src="{{asset('assets/js/components/autocomplete/bootstrap-autocomplete.js')}}">
</script>
<script type="application/javascript" src="{{ asset('assets/js/myScript/materials/stock_in.js') }}"></script>
<script type="application/javascript"
    src="{{ asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
