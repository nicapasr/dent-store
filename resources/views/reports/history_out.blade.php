@extends('layouts.master')

@section('title', 'ประวัติการเบิกวัสดุ')
@section('title_page', 'ประวัติการเบิกวัสดุ')

@section('content')
<!-- Search form -->
<div class="row d-flex justify-content-center pb-0 pb-sm-5">
    <div class="navbar-search navbar-search-dark form-inline">
        <div class="input-group mb-0">
            <div class="input-group input-group-alternative input-group-merge">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input id="input_history_out_search_bar" class="form-control" placeholder="ค้นหา" type="search" autocomplete="off">
            </div>
        </div>
        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
            aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
</div>
<div class="card">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-6 pr-0">
                <h3 class="mb-0">ประวัติการเบิกวัสดุ</h3>
            </div>
            <div class="col-6 pl-0">
                <div class="input-group float-right p-0 d-block d-sm-none mb-0">
                    <div class="input-group input-group-alternative input-group-merge" style="border-radius: 2rem;">
                        <input id="input_history_out_search_bar" class="form-control bg-default" style="border-radius: 2rem;"
                            placeholder="ค้นหา" type="search" name="search">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="table_history_out">
        @include('table.history_out_table_body')
    </div>
    {{-- <div class="col">
                            <form class="form-inline justify-content-end" action="{{ url('exportStockIn') }}"
    method="get">
    <div class="row mx-1">
        <div class="form-group mx-1">
            <select id="month" class="form-control browser-default custom-select w-100 m-0" name="month">

                @foreach ($months as $key => $month)
                <option value="{{ $key }}" {{ date('m') == $key ? 'selected' : '' }}>
                    {{ $month }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mx-1">
            <select id="year" class="form-control browser-default custom-select w-100 m-0" name="year">

                @foreach ($years as $year)
                <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group ml-1">
            <button class="btn btn-success" type="submit">Download</button>
        </div>
    </div>
    </form>
</div> --}}
</div>
{{-- </div> --}}

@include('modal.history.history_out_modal')

<script type="application/javascript" src="{{asset('assets/js/myScript/historys/history_out.js')}}"></script>
<script type="application/javascript">
$(() => {
    @if(count($errors) > 0)
        $('#stock_out_list_modal').modal('show')
    @endif
});
</script>
@endsection
