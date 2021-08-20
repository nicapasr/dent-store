$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    let text = $("#input_stock_in_search_bar").val();
    fetch_data(page, text);
});

function fetch_data(page, text = "") {
    $.get(
        "/admin/history/in/pagination?page=" +
            page +
            "&searchText=" +
            text,
        res => {
            const jsonData = JSON.parse(res);
            if (jsonData.HEADER.status == 200) {
                if (jsonData.BODY != null && jsonData.BODY != "") {
                    const data = jsonData.BODY;
                    $("#table_history_in").html("");
                    $("#table_history_in").html(data);
                }
            }
        }
    );
}

//in
$('#input_stock_in_search_bar').on('keyup', function () {
    const searchText = $(this).val();
    const page = 1;

    fetch_data(page, searchText);

    // const formData = {
    //     _token: _token,
    //     search: searchText
    // }

    // $.get("/admin/history/in/search", formData,
    //     (res) => {
    //         if (res.status == 200) {
    //             const data = res.data;
    //             $('#table_history_in').html(data);
    //         }
    //     }
    // );

});

$('#btn_download_excel').on('click', function () {
    const _token = $('meta[name="csrf-token"]').attr('content');
    const formData = {
        from: '2021-04-18',
        to: '2021-04-20',
        stock: 'in',
        _token: _token
    }

    $.post("in/download/excel", formData,
        function (data) {

        },
        "json"
    ).catch((error) => {
        if (error.status == 400) {
            let errorMsg = ''
            for (const key in error.responseJSON.message) {
                let temp = error.responseJSON.message[key]
                errorMsg = errorMsg + temp + '<br>'
            }

            Swal.fire({
                title: 'พบข้อผิดพลาด!',
                html: errorMsg,
                icon: 'error',
                showConfirmButton: true,
            });
        } else {
            Swal.fire({
                title: 'พบข้อผิดพลาด!',
                html: error.responseJSON.message,
                icon: 'error',
                showConfirmButton: true,
            });
        }
    });
});
