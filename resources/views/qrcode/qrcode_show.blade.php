@extends('layouts.master')

@section('title', 'QRCode')
@section('title_page', 'QRCode')

@section('content')
    <div class="row d-flex justify-content-center d-print-none">
        <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-3">
            <div class="input-group p-0 mb-5">
                <div class="input-group input-group-alternative input-group-merge" style="border-radius: 2rem;">
                    <input id="qr_code_search_bar" class="form-control bg-default" style="border-radius: 2rem;" placeholder="ค้นหา"
                        type="search" name="search">
                </div>
            </div>
        </div>
    </div>
    @isset($materials)
        <div id="div_qrcode">
            @include('table.qrcode_table_body')
        </div>
    @endisset
    <script type="application/javascript" src="{{ asset('assets/js/myScript/qrcode.js') }}"></script>
@endsection
