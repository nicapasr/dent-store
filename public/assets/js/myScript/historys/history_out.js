$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    let text = $("#input_history_out_search_bar").val();
    fetch_data(page, text);
});

function fetch_data(page, text = "") {
    $.get(
        "/admin/history/out/pagination?page=" +
            page +
            "&searchText=" +
            text,
        res => {
            const jsonData = JSON.parse(res);
            if (jsonData.HEADER.status == 200) {
                if (jsonData.BODY != null && jsonData.BODY != "") {
                    const data = jsonData.BODY;
                    $("#table_history_out").html("");
                    $("#table_history_out").html(data);
                }
            }
        }
    );

    // $.get("/history/out/pagination?page=" + page, (res) => {
    //     if (res.status == 200) {
    //         const data = res.data;
    //         $('#table_history_out').html(data);
    //     }
    // });
}

//out
$('#input_history_out_search_bar').on('keyup', function (e) {
    const searchText = $(this).val();
    const page = 1;

    // console.log(searchText);

    fetch_data(page, searchText);

    // const formData = {
    //     _token: _token,
    //     search: searchText
    // }

    // $.get("/history/out/search", formData,
    //     (res) => {
    //         if (res.status == 200) {
    //             const data = res.data;
    //             $('#table_history_out').html(data);
    //         }
    //     }
    // );

});

// $('table td #btn_edit').on('click', function () {
//     let material = $(this).data('material')
//     let maxValue = material.material_table.m_balance + material.value_out
//     // console.log(material)
//     $('#input_m_name').val(material.material_table.m_name)
//     $('#input_id_stock_out').val(material.id_stock_out)
//     $('#input_room').val(material.room)
//     $('#input_m_balance').val(maxValue)
//     $('#input_width_draw_value').val(material.value_out)
//     $('#stock_out_list_modal').modal('show')
// });

// $('table td #btn_cancel').on('click', function () {
//     let material = $(this).data('material')
//     Swal.fire({
//         title: 'แจ้งเตือน!',
//         text: 'คุณต้องการยกเลิกการเบิกวัสดุใช่หรือไม่?',
//         icon: 'warning',
//         showConfirmButton: true,
//         showCancelButton: true,
//         cancelButtonColor: '#f5365c'
//     }).then(function (confirm) {
//         if (!confirm.value) {
//             return
//         }

//         $.get('out/cancel/' + material,
//             (res) => {
//                 // console.log(res)
//                 Swal.fire({
//                     title: 'สำเร็จ',
//                     text: 'ยกเลิกรายการเบิกวัสดุ ' + res.data.material_table.m_name + ' คุณ' + res.data.members.fname + ' ' + res.data.members.lname + ' เรียบร้อยแล้ว',
//                     icon: 'success',
//                     showConfirmButton: true,
//                 }).then((confirm) => {
//                     if (!confirm.value) {
//                         return
//                     }

//                     window.location.reload()
//                 })
//             }
//         ).catch(
//             (error) => {
//                 // console.error(error)
//                 Swal.fire({
//                     title: 'ขออภัย!',
//                     text: error.responseJSON.message,
//                     icon: 'error',
//                     showConfirmButton: true,
//                 })
//             }
//         );
//     });
// });
