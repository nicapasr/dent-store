@extends('layouts.master')

@if (auth()->user()->permission == 1)
@section('title', 'เบิก')
@section('title_page', 'เบิก')
@endif


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
                    <input id="search_bar" class="form-control" placeholder="ค้นหา" type="search" autocomplete="off">
                </div>
            </div>
            <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main"
                aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
</div>
<div class="col-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col-6 pr-0">
                    <h3 class="mb-0">ตารางวัสดุ</h3>
                </div>
                {{-- <div class="col-6 pl-0">
                    <div class="input-group float-right p-0 d-block d-sm-none mb-0">
                        <div class="input-group input-group-alternative input-group-merge" style="border-radius: 2rem;">
                            <input id="search_bar" class="form-control bg-default" style="border-radius: 2rem;"
                                placeholder="ค้นหา" type="search" name="search">
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <div id="table_data">
            @include('table.stock_out_table_body')
        </div>
    </div>
</div>
@include('modal.material.modal_stock_out', ['wareHouses' => $wareHouses, 'units' => $units])

<script type="application/javascript" src="{{ asset('assets/js/myScript/materials/stock_out.js') }}"></script>
<script type="application/javascript" src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}">
</script>

<script type="application/javascript">
    $(document).ready(function () {
        @if(count($errors) > 0)
            $('#material_add_modal').modal('show');
        @endif
    });
</script>
@endsection
