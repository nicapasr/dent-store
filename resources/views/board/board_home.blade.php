@php
use App\Http\Controllers\Util\ConvertDateThai;
use Carbon\Carbon;
use App\Http\Controllers\GlobalConstant;
@endphp

@extends('layouts.master')

@section('title', 'ระบบจัดการคงคลังวัสดุทันตกรรม : DENT STORE')
@section('title_page', 'หน้าหลัก')

@section('topnevbar')
@include('layouts.topnevbar')
@endsection

@section('content')
<div class="row">
    {{-- EXP --}}
    <div class="col-xl-6 {{$materialExp->count() > 0 ? '' : 'd-none'}}">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">วัสดุใกล้หมดอายุ</h3>
                    </div>
                </div>
            </div>
            {{-- เบิกวันนี้ --}}
            <div class="table-responsive">
                <!-- exp table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">วันที่หมดอายุ</th>
                            <th scope="col">รหัสวัสดุ</th>
                            <th scope="col">ชื่อวัสดุ</th>
                            <th scope="col">จำนวน(หน่วย)</th>
                            {{-- <th scope="col">อนุมัติ</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materialExp as $item)
                        <tr>
                            <td class="text-yellow">
                                {{ConvertDateThai::toDateThai($item->m_exp_date)}}
                            </td>
                            <td>
                                {{$item->m_code}}
                            </td>
                            <td>
                                {{$item->m_name}}
                            </td>
                            <td>
                                {{$item->m_balance}} {{$item->materialUnit->unit_name}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6 {{$materialExped->count() > 0 ? '' : 'd-none'}}">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">วัสดุหมดอายุ</h3>
                    </div>
                </div>
            </div>
            {{-- เบิกวันนี้ --}}
            <div class="table-responsive">
                <!-- exp table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">วันที่หมดอายุ</th>
                            <th scope="col">รหัสวัสดุ</th>
                            <th scope="col">ชื่อวัสดุ</th>
                            <th scope="col">จำนวน(หน่วย)</th>
                            {{-- <th scope="col">อนุมัติ</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materialExped as $item)
                        <tr>
                            <td class="text-red">
                                {{ConvertDateThai::toDateThai($item->m_exp_date)}}
                            </td>
                            <td>
                                {{$item->m_code}}
                            </td>
                            <td>
                                {{$item->m_name}}
                            </td>
                            <td>
                                {{$item->m_balance}} {{$item->materialUnit->unit_name}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    {{-- STOCK --}}
    <div class="col-xl-6 {{$outOfStock->count() > 0 ? '' : 'd-none'}}">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">วัสดุใกล้หมดสต๊อก</h3>
                    </div>
                </div>
            </div>
            {{-- วัสดุใกล้หมดสต๊อก --}}
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">วันที่หมดอายุ</th>
                            <th scope="col">รหัสวัสดุ</th>
                            <th scope="col">ชื่อวัสดุ</th>
                            <th scope="col">คงเหลือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outOfStock as $item)
                        <tr>
                            <td>
                                @if (isset($item->m_exp_date))
                                {{$item->m_exp_date}}
                                @else
                                {{"-"}}
                                @endif
                            </td>
                            <td>
                                {{$item->m_code}}
                            </td>
                            <td>
                                {{$item->m_name}}
                            </td>
                            <td>
                                {{$item->m_balance}} {{$item->materialUnit->unit_name}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6 {{$outOfStocked->count() > 0 ? '' : 'd-none'}}">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">วัสดุหมดสต๊อก</h3>
                    </div>
                </div>
            </div>
            {{-- วัสดุหมดสต๊อก --}}
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">วันที่หมดอายุ</th>
                            <th scope="col">รหัสวัสดุ</th>
                            <th scope="col">ชื่อวัสดุ</th>
                            <th scope="col">ประเภทวัสดุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outOfStocked as $item)
                        <tr>
                            <td>
                                @if (isset($item->m_exp_date))
                                {{$item->m_exp_date}}
                                @else
                                {{"-"}}
                                @endif
                            </td>
                            <td>
                                {{$item->m_code}}
                            </td>
                            <td>
                                {{$item->m_name}}
                            </td>
                            <td>
                                {{$item->materialType->type_name}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
