@extends('layouts.master')

@section('title', 'นำเข้าวัสดุด้วย QR-Code')
@section('title_page', 'นำเข้าวัสดุด้วย QR-Code')

@section('content')
<div class="text-center">
    <h3>แสกน QR-Code เพื่อเพิ่มวัสดุ</h3>
</div>
<div class="col-12">
    <qrcode-scan-in-component :ware_house="{{$warehouses}}"></qrcode-scan-in-component>
</div>
@endsection
