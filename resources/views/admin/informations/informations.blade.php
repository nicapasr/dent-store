@extends('layouts.master')

@section('title', 'จัดการข้อมูลพื้นฐาน')
@section('title_page', 'จัดการข้อมูลพื้นฐาน')

@section('content')
<div class="nav-wrapper">
    <h3>เลือกหัวข้อที่ต้องการแก้ไข</h3>
    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
        <li id="user-tab" class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1"
                aria-selected="true"><i class="ni ni-single-02 mr-2"></i>เปลี่ยนสิทธิ์ผู้ใช้งาน</a>
        </li>
        <li id="dep-tab" class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2"
                aria-selected="false"><i class="fas fa-bars mr-2"></i>สมาชิก</a>
        </li>
        <li id="waerhouse-tab" class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3"
                aria-selected="false"><i class="fas fa-warehouse mr-2"></i>คลัง</a>
        </li>
        <li id="unit-tab" class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4"
                aria-selected="false"><i class="fas fa-boxes mr-2"></i>หน่วยวัสดุ</a>
        </li>
        {{-- <li class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" id="type-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab"
                aria-controls="tabs-icons-text-5" aria-selected="false"><i
                    class="fas fa-syringe mr-2"></i>ประเภทวัสดุ</a>
        </li> --}}
        <li id="material-tab" class="nav-item custom-nev-info">
            <a class="nav-link mb-sm-3 mb-md-0" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-5"
                aria-selected="false"><i class="fas fa-syringe mr-2"></i>วัสดุ</a>
        </li>
    </ul>
</div>
<div class="card">
    <div class="card-body px-0">
        <div class="tab-content">
            <div class="tab-pane fade" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1">
                {{-- unit card header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">เปลี่ยนสิทธิ์ผู้ใช้งาน</h3>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-success text-white" id="btn_add_user" data-toggle="modal"
                                data-target="#add-user-modal">เพิ่มผู้ใช้งาน</a>
                        </div>
                    </div>
                </div>

                {{-- users table --}}
                @include('table.users_table')

            </div>

            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">

                {{-- members card header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">สมาชิก</h3>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-success text-white" data-toggle="modal"
                                data-target="#add-member-modal">เพิ่มสมาชิก</a>
                        </div>
                    </div>
                </div>

                {{-- members table --}}
                @include('table.members_table')

            </div>
            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">

                {{-- wearhouse header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">คลัง</h3>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-success text-white" data-toggle="modal"
                                data-target="#add-warehouse-modal">เพิ่มข้อมูล</a>
                        </div>
                    </div>
                </div>

                {{-- wearhouse table --}}
                @include('table.warehouse_table')

            </div>
            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">

                {{-- units header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">หน่วยวัสดุ</h3>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-success text-white" data-toggle="modal"
                                data-target="#add-unit-modal">เพิ่มข้อมูล</a>
                        </div>
                    </div>
                </div>

                {{-- units table --}}
                @include('table.units_table')
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">

                {{-- type header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">ประเภทวัสดุ</h3>
                        </div>
                        <div class="col text-right">
                            <a class="btn btn-success text-white" data-toggle="modal"
                                data-target="#add-type-modal">เพิ่มข้อมูล</a>
                        </div>
                    </div>
                </div>

                {{-- type table --}}
                {{-- @include('table.types_table') --}}
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6">
                {{-- unit card header --}}
                <div class="card-header pt-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="m-0">วัสดุ</h3>
                        </div>
                        {{-- <div class="col text-right">
                            <a class="btn btn-success text-white" id="add-user-modal" data-toggle="modal" data-target="#add-user-modal"
                                data-dep="{{$agency}}">เพิ่มข้อมูล</a>
                    </div> --}}
                </div>
            </div>

            {{-- materials table --}}
            @include('table.materials_table')

        </div>

    </div>
</div>
</div>
@include('modal.warehouseModal')
@include('modal.unitModal')
@include('modal.add_user_modal')
@include('modal.editUserModal')
@include('modal.material')
@include('modal.memberModal')
<script type="application/javascript" src="{{asset('assets/js/myScript/informations.js')}}"></script>
@endsection
