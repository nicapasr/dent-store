@extends('layouts.master')

@section('title', 'โปรไฟล์')
@section('title_page', 'ข้อมูลโปรไฟล์')

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-xl-10">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">แก้ไขโปรไฟล์</h3>
                    </div>
                    <div class="col-4 text-right">
                        {{-- <a id="btn_line_noti" href="{{$line}}"
                            class="btn btn-sm text-white @if(auth()->user()->line_token) disabled @endif"
                            style="background-color: #28c368"><i class="ni ni-bell-55 align-middle"></i> สมัคร line
                            notify</a> --}}
                        <a id="btn_submit" href="" class="btn btn-sm btn-primary">บันทึก</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h6 class="heading-small text-muted mb-4">ข้อมูลบัญชี</h6>
                <form id="form_update_detail" action="{{route('user.detail.update')}}" method="post">
                    @csrf
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-username">Username</label>
                                    <input type="text" id="input-username" class="form-control" readonly name="username"
                                        value="{{auth()->user()->username}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_permission">ระดับ</label>
                                    <input type="text" id="input_permission"
                                        class="form-control @error('permission') is-invalid @enderror" readonly
                                        name="permission" value="{{auth()->user()->first_name}}"
                                        onkeydown="clearErrors('permission')">

                                    @error('permission')
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
                                    <label class="form-control-label" for="input_first_name">ชื่อ</label>
                                    <input type="text" id="input_first_name"
                                        class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                        value="{{auth()->user()->first_name}}"
                                        onkeydown="clearErrors('input_first_name')">

                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input_last_name">นามสกุล</label>
                                    <input type="text" id="input_last_name"
                                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                        value="{{auth()->user()->last_name}}"
                                        onkeydown="clearErrors('input_last_name')">

                                    @error('last_name')
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
                                    <label class="form-control-label" for="input_phone">เบอร์โทรศัพท์</label>
                                    <input type="tel" id="input_phone"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{auth()->user()->phone}}" onkeydown="clearErrors('input_phone')">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="select_department">สังกัด</label>
                                    <select id="select_department"
                                        class="form-control @error('department') is-invalid @enderror" name="department"
                                        onchange="clearErrors('department');">

                                        <option value="" disabled selected>เลือกสังกัด</option>
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->id_dep }}"
                                            {{ auth()->user()->department == $department->id_dep ? 'selected' : '' }}>
                                            {{ $department->dep_name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
function clearErrors(id) {
    $('#' + id).removeClass('is-invalid');
}

$(document).ready(function () {
    $('#btn_submit').on('click', (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'ยืนยันการบันทึกข้อมูล!',
            icon: 'warning',
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonColor: '#f5365c'
        }).then((confirm) => {
            if (!confirm.value) {
                return
            }

            $('#form_update_detail').submit();
        });

    });
});
</script>
@endsection
