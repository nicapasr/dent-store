@extends('layouts.master')

@section('title', 'ระบบจัดการคงคลังวัสดุทันตกรรม : DENT STORE')
@section('title_page', 'วัสดุใกล้หมดสต๊อก')

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-sm-8 col-md-10">
        <div class="card">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">ตารางวัสดุที่ใกล้หมดสต๊อก</h3>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <!-- Projects table -->
                <table id="search_table" class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">รหัส</th>
                            <th scope="col">ชื่อ</th>
                            <th scope="col">ประเภท</th>
                            <th scope="col">ทั้งหมด</th>
                            <th scope="col">คงเหลือ</th>
                            <th scope="col">หน่วยนับ</th>
                            <th scope="col">นำเข้า</th>
                        </tr>
                    </thead>
                    @foreach ($outStock as $item)
                    <tr>
                        <th scope="row">
                            {{$item->m_code}}
                        </th>
                        <th scope="row">
                            {{$item->m_name}}
                        </th>
                        <th scope="row">
                            {{$item->materialType->type_name}}
                        </th>
                        <th scope="row">
                            {{$item->m_total}}
                        </th>
                        <th id="row_balance" scope="row" style="color: #2ecc71">
                            {{$item->m_balance}}
                        </th>
                        <th scope="row">
                            {{$item->materialUnit->unit_name}}
                        </th>
                        <th scope="row">
                            <a class="btn " href="update?m_code={{$item->m_code}}">
                                <i class='fas fa-arrow-alt-circle-down' data-toggle="tooltip" data-placement="top"
                                    title="คลิกเพื่อเพิ่มวัสดุ"></i>
                            </a>
                            {{-- <button type="button" class="btn " data-toggle="modal" data-placement="top" title="คลิกดู QRCode" data-target="#modal-from"
                                data-m-code="{{$item->m_code}}" data-m-name="{{$item->m_name}}">
                            <i data-toggle="tooltip" data-placement="top" title="คลิกดู QRCode"
                                class="fas fa-qrcode"></i>
                            </button> --}}
                        </th>
                    </tr>
                    @endforeach
                </table>
            </div>
                <div id="cardFooter" class="card-footer">
                    <div class="row">
                        <div class="col"></div>
                            <div class="col-auto">
                            @isset($outStock)
                                {{ $outStock->links() }}
                            @endisset
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


@endsection
