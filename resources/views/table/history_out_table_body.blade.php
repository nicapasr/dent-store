@php use App\Http\Controllers\Util\ConvertDateThai; @endphp

<div class="table-responsive">
    <!-- Projects table -->
    <table class="table align-items-center table-flush">
        <thead class="thead-light text-center">
            <tr>
                <th scope="col">วันที่ขอเบิก</th>
                <th scope="col">วันที่หมดอายุ</th>
                <th scope="col">ห้องที่เบิก</th>
                <th scope="col">ผู้ขอเบิก</th>
                <th scope="col">รหัสวัสดุ</th>
                <th scope="col">ชื่อวัสดุ</th>
                <th scope="col">จำนวนที่เบิก</th>
                {{-- <th scope="col">รวมราคา(บาท)</th> --}}
                <th scope="col">แก้ไข/ยกเลิก</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($stockOut as $itemStockOut)
            <tr>
                <td scope="row">
                    {{ ConvertDateThai::toDateThai($itemStockOut->date_out) }}
                </td>
                <td scope="row">
                    {{ isset($itemStockOut->balances->b_exp_date) ? ConvertDateThai::toDateThai($itemStockOut->balances->b_exp_date) : '-' }}
                </td>
                <td scope="row">
                    {{ $itemStockOut->room }}
                </td>
                <td scope="row">
                    {{ $itemStockOut->members->fname }} {{ $itemStockOut->members->lname }}
                </td>
                <td scope="row">
                    {{ $itemStockOut->balances->materials->m_code }}
                </td>
                <td scope="row" class="text-left">
                    {{ $itemStockOut->balances->materials->m_name }}
                </td>
                <td scope="row">
                    {{ $itemStockOut->value_out }} {{ $itemStockOut->balances->materials->materialUnit->unit_name }}
                </td>
                {{-- <td scope="row">
                    {{ $itemStockOut->total_price_out }}
                </td> --}}
                <td>
                    <button id="btn_edit" class="btn text-white bg-yellow" data-toggle="modal"
                        onclick="edit({{$itemStockOut}})">แก้ไข</button>
                    <button id="btn_cancel" class="btn btn-danger" data-toggle="modal"
                        onclick="cancel({{$itemStockOut->id_stock_out}})">ยกเลิก</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card-footer py-4">
    <div class="row">
        <div class="col"></div>
        <div id="div_out_pagination" class="col-auto">
            {!! $stockOut->links() !!}
        </div>
    </div>
</div>
<script type="application/javascript">
    function edit(stockOut) {
    let maxValue = stockOut.balances.b_value + stockOut.value_out
    $('#input_m_name').val(stockOut.balances.materials.m_name)
    $('#input_id_stock_out').val(stockOut.id_stock_out)
    $('#input_room').val(stockOut.room)
    $('#input_m_balance').val(maxValue)
    $('#input_width_draw_value').val(stockOut.value_out)
    $('#stock_out_list_modal').modal('show')
}

function cancel(stockOutId) {
    Swal.fire({
        title: 'แจ้งเตือน!',
        text: 'คุณต้องการยกเลิกการเบิกวัสดุใช่หรือไม่?',
        icon: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: '#f5365c'
    }).then(function (confirm) {
        if (!confirm.value) {
            return
        }

        $.get('out/cancel/' + stockOutId,
            (res) => {
                const jsonData = JSON.parse(res);
                const fname = jsonData.BODY.members.fname;
                const lname = jsonData.BODY.members.lname;
                const name = jsonData.BODY.balances.materials.m_name;
                const value = jsonData.BODY.value_out;
                const unit = jsonData.BODY.balances.materials.material_unit.unit_name;

                Swal.fire({
                    title: 'สำเร็จ',
                    text: 'ยกเลิกรายการเบิกวัสดุ ' + name + ' คุณ' + fname + ' ' + lname + 'จำนวน ' + value + ' ' + unit + ' เรียบร้อยแล้ว',
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
