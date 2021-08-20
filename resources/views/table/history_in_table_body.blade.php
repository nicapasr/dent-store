@php use App\Http\Controllers\Util\ConvertDateThai; @endphp

<div class="table-responsive">
    <!-- Projects table -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light text-center">
            <tr>
                <th scope="col">วันที่นำเข้า</th>
                <th scope="col">วันที่หมดอายุ</th>
                <th scope="col">รหัส</th>
                <th scope="col">ชื่อวัสดุ</th>
                <th scope="col">นำเข้าจาก</th>
                <th scope="col">จำนวน</th>
                {{-- <th scope="col">รวมราคา(บาท)</th> --}}
                <th scope="col">แก้ไข/ยกเลิก</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @if ($stockIn->count() > 0)
            @foreach ($stockIn as $item)
            <tr>
                <td scope="row">
                    {{ ConvertDateThai::toDateThai($item->date_in) }}
                </td>
                <td scope="row">
                    {{ isset($item->balances->b_exp_date) ? ConvertDateThai::toDateThai($item->balances->b_exp_date) : '-' }}
                </td>
                <td scope="row">
                    {{ $item->balances->materials->m_code }}
                </td>
                <td scope="row" class="text-left">
                    {{ $item->balances->materials->m_name }}
                </td>
                <td scope="row">
                    {{ $item->warehouses->warehouse_name }}
                </td>
                <td scope="row">
                    {{ $item->value_in }} {{ $item->balances->materials->materialUnit->unit_name }}
                </td>
                {{-- <td scope="row">
                    {{ $item->total_price_in }}
                </td> --}}
                <td>
                    <button id="btn_edit" class="btn text-white bg-yellow" data-toggle="modal"
                        onclick="edit({{$item}})">แก้ไข</button>
                    <button id="btn_cancel" class="btn btn-danger" data-toggle="modal"
                        onclick="cancel({{$item->id_stock_in}})">ยกเลิก</button>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <h4 class="text-center text-gray">
                    ไม่พบรายการ
                </h4>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="card-footer py-4">
    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            {!! $stockIn->links() !!}
        </div>
    </div>
</div>
<script type="application/javascript">
    function edit(stockIn) {
    $('#input_m_code').val(stockIn.balances.materials.m_code)
    $('#input_m_name').val(stockIn.balances.materials.m_name)
    $('#input_id_stock_in').val(stockIn.id_stock_in)
    $('#input_value').val(stockIn.value_in)
    $('#input_m_unit').val(stockIn.balances.materials.material_unit.unit_name)
    $('#history_in_modal').modal('show')
}

function cancel(id) {
    Swal.fire({
        title: 'แจ้งเตือน!',
        text: 'คุณต้องการยกเลิกการนำเข้าวัสดุใช่หรือไม่?',
        icon: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: '#f5365c'
    }).then(function (confirm) {
        if (!confirm.value) {
            return
        }

        $.get('in/cancel/' + id,
            (res) => {
                const jsonData = JSON.parse(res);
                const name = jsonData.BODY.balances.materials.m_name;
                const value = jsonData.BODY.value_in;
                const unit = jsonData.BODY.balances.materials.material_unit.unit_name;

                Swal.fire({
                    title: 'สำเร็จ',
                    text: 'ยกเลิกรายการนำเข้าวัสดุ ' + name + ' จำนวน ' + value + ' ' + unit + ' เรียบร้อยแล้ว',
                    icon: 'success',
                    showConfirmButton: true,
                }).then((confirm) => {
                    if (!confirm.value) {
                        return
                    }

                    window.location.reload()
                })
            }
        ).catch(
            (error) => {
                // console.error(error)
                Swal.fire({
                    title: 'ขออภัย!',
                    text: error.responseJSON.message,
                    icon: 'error',
                    showConfirmButton: true,
                })
            }
        );
    });
}
</script>
