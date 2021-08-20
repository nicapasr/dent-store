@extends('layouts.master')

@section('title', 'เบิกวัสดุด้วย QR-Code')
@section('title_page', 'เบิกวัสดุด้วย QR-Code')

@section('content')
<div class="text-center">
    <h3>แสกน QR-Code เพื่อเบิกวัสดุ</h3>
</div>
<div class="col-12">
    <qrcode-scan-out-component></qrcode-scan-out-component>
</div>
@endsection
