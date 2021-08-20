$(document).on("click", ".pagination a", function(event) {
    event.preventDefault();
    var page = $(this)
        .attr("href")
        .split("page=")[1];
    let text = $("#search_bar").val();
    fetch_data(page, text);
});

function fetch_data(page, text = "") {
    $.get(
        "/admin/material/stock/out/pagination?page=" +
            page +
            "&searchText=" +
            text,
        res => {
            const jsonData = JSON.parse(res);
            if (jsonData.HEADER.status == 200) {
                if (jsonData.BODY != null && jsonData.BODY != "") {
                    const data = jsonData.BODY;
                    $("#table_data").html("");
                    $("#table_data").html(data);
                }
            }
        }
    );
}

$("#search_bar").on("keyup", function() {
    const searchText = $(this).val();
    const page = 1;

    // console.log(searchText);

    fetch_data(page, searchText);
});

$(".select").autoComplete({
    resolver: "ajax",
    bootstrapVersion: "4",
    minLength: 3,
    valueKey: "value",
    events: {
        search: function(qry, callback) {
            const _token = $('meta[name="csrf-token"]').attr("content");

            const formData = {
                member: qry,
                _token: _token
            };

            $.post("/admin/material/stock/out/members", formData, res => {
                if (res.status == 200) {
                    callback(res.data);
                }
            }).catch(error => {
                console.error(error.responseJSON);
            });
        }
    }
});

$(".select").on("autocomplete.select", function(evt, item) {
    // console.log(item);
    $("input[name=member]").val(item.value);
});

function clearErrors(id) {
    $("#" + id).removeClass("is-invalid");
}

function clearInput() {
    $("#input_room").removeClass("is-invalid");
    $("#input_width_draw_value").removeClass("is-invalid");

    $("#input_room").val("");
    $("#input_width_draw_value").val("");
}

// $('.modal-content #bt_submit').on('click', function (e) {
//     e.preventDefault();
//     const form = $('#form_stock_out');
//     form.submit()
//     console.log(form)
// });
