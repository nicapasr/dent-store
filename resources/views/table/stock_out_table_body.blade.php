<?php use App\Http\Controllers\Util\ConvertDateThai;?>

{{-- @foreach ($materials as $item)
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="card overflow-auto">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-4 d-flex justify-content-center align-items-center">
                        <div class="text-center">
                            <h2 class="d-none d-sm-block m-0">คงเหลือ</h2>
                            <h2
                                class="@if($item->m_balance == 0) text-danger @elseif($item->m_balance <= 7) text-yellow @else text-success @endif m-0">
                                {{$item->m_balance}}</h2>
</div>
</div>
<div class="col-4 d-flex justify-content-center align-items-center">
    <div class="text-center">
        <h2 class="d-none d-sm-block m-0">{{$item->m_code}}</h2>
        <p class="mb-0">{{$item->m_name}}</p>
    </div>
</div>
<div class="col-4 d-flex justify-content-center align-items-center">
    @if (auth()->user()->permission == 1)
    <a class="btn btn-success text-white w-100" data-toggle="tooltip" data-placement="top" title="คลิกเพื่อเพิ่มวัสดุ"
        href="/admin/material/stock/in/{{ $item->m_code }}">
        <div class="row justify-content-center align-items-center">
            <i class='fas fa-arrow-alt-circle-down mr-0 mr-sm-1'></i>
            <h4 class="text-white d-none d-sm-block m-0">นำเข้า</h4>
        </div>
    </a>
    @endif
    <a class="btn btn-danger text-white w-100 @if($item->m_balance == 0) disabled @endif" data-toggle="tooltip"
        data-placement="top" title="คลิกเพื่อเบิกวัสดุ" href="{{$path}}{{$item->m_code}}">
        <div class="row justify-content-center align-items-center">
            <i class='fas fa-arrow-alt-circle-up mr-0 mr-sm-1'></i>
            <h4 class="text-white d-none d-sm-block m-0">เบิกวัสดุ</h4>
        </div>
    </a>
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach
<div class="row d-flex justify-content-center">
    <div class="col-auto">
        {!! $materials->links() !!}
    </div>
</div> --}}

{{-- data table --}}
{{-- <div class="col-12">
    <div class="card">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col-6 pr-0">
                    <h3 class="mb-0">ตารางวัสดุ</h3>
                </div>
                <div class="col-6 pl-0">
                    <div class="input-group float-right p-0 d-block d-sm-none mb-0">
                        <div class="input-group input-group-alternative input-group-merge" style="border-radius: 2rem;">
                            <input id="search_bar" class="form-control bg-default" style="border-radius: 2rem;"
                                placeholder="ค้นหา" type="search" name="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="table_data">
            @include('table.dashboard_table_body')
        </div>
    </div>
</div> --}}
<div class="table-responsive">
    <table id="search_table" class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th scope="col">รหัส</th>
                <th scope="col">ชื่อ</th>
                <th class="text-center" scope="col">วันหมดอายุ</th>
                <th class="text-center" scope="col">คงเหลือ</th>
                <th scope="col">หน่วยนับ</th>
                <th class="text-center" scope="col">นำเข้า / เบิก</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($balances as $item)
            <tr>
                <td scope="row">
                    {{$item->materials->m_code}}
                </td>
                <td scope="row">
                    {{$item->materials->m_name}}
                </td>
                <td scope="row" class="text-center">
                    {{isset($item->b_exp_date) ? ConvertDateThai::toDateThai($item->b_exp_date) : "-"}}
                </td>
                <td id="row_balance" scope="row"
                    class="text-center bg-secondary @if($item->b_value == 0) text-danger @elseif($item->b_value <= 7) text-yellow @else text-success @endif"
                    style="font-size: 20px">
                    {{$item->b_value}}
                </td>
                <td scope="row">
                    {{$item->materials->materialUnit->unit_name}}
                </td>
                <td class="text-center" scope="row">
                    <a id="a_stock_out"
                        class="btn btn-danger text-white @if($item->b_value == 0) disabled @endif px-2 py-1"
                        data-toggle="modal" data-item="{{$item}}">
                        <i class='fas fa-arrow-alt-circle-up'></i>
                        เบิกวัสดุ
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card-footer py-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            {!! $balances->links() !!}
        </div>
    </div>
</div>

<script type="application/javascript">
    //width draw
$("table tbody td #a_stock_out").on("click", function(e) {
    e.preventDefault();
    const item = $(this).data("item");

    if (item) {
        if (item.b_exp_date != null) {
            $("#input_m_exp_date").val(item.b_exp_date);
            $("#div_exp_date").removeClass("d-none");
        } else {
            $("#div_exp_date").addClass("d-none");
        }

        $("#input_id").val(item.id);
        $("#input_m_code").val(item.material_id);
        $("#input_m_name").val(item.materials.m_name);
        $("#input_balance").val(item.b_value);

        clearInput();
        // $('#input_unit option[value='+ data.m_unit +']').prop('selected', true)
        $("#material_add_modal").modal("show");
    }

    // $.get("/admin/material/detail/" + id, (res) => {
    //     const jsonData = JSON.parse(res);
    //     if (jsonData.HEADER.status == 200) {
    //         const data = jsonData.BODY;
    //         console.log(data);
    //         if (data.m_exp_date != null) {
    //             $('#input_m_exp_date').val(data.balances[0].b_exp_date);
    //             $('#div_exp_date').removeClass('d-none');
    //         } else {
    //             $('#div_exp_date').addClass('d-none');
    //         }

    //         $('#input_material_id').val(data.id);
    //         $('#input_m_code').val(data.m_code);
    //         $('#input_m_name').val(data.m_name);
    //         $('#input_balance').val(data.m_balance);

    //         clearInput()
    //         // $('#input_unit option[value='+ data.m_unit +']').prop('selected', true)
    //         $('#material_add_modal').modal('show');
    //     }
    // });
});
</script>
