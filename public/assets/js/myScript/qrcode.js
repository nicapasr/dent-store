$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    let text = $("#qr_code_search_bar").val();
    fetch_data(page, text);
});

$("#qr_code_search_bar").on("keyup", function(e) {
    const _token = $('meta[name="csrf-token"]').attr("content");
    const searchText = $(this).val();
    const page = 1;

    fetch_data(page, searchText);
});

function fetch_data(page, text = "") {
    $.get(
        "/admin/qrcode/search/pagination?page=" + page + "&searchText=" + text,
        res => {
            const jsonData = JSON.parse(res);
            if (jsonData.HEADER.status === 200) {
                if (jsonData.BODY != null && jsonData.BODY != "") {
                    const data = jsonData.BODY;
                    $("#div_qrcode").html("");
                    $("#div_qrcode").html(data);
                }
            }
        }
    );
}
