$(function() {
    $(".datepicker").datepicker({
        format: "dd-mm-yyyy"
    });
});

$("#checkbox_date_exp").on("click", () => {
    let isChecked = $("#checkbox_date_exp")[0].checked;
    if (isChecked) {
        $("#input_date_exp").prop("disabled", false);
    } else {
        $("#input_date_exp").val("");
        $("#input_date_exp").prop("disabled", true);
    }
});

$("#input_search_bar").on("keyup", function() {
    $(".card").addClass("d-none");

    const _token = $('meta[name="csrf-token"]').attr("content");
    const searchText = $(this).val();

    const formData = {
        _token: _token,
        search: searchText
    };

    $.get("/admin/material/stock/in/search", formData, res => {
        const jsonData = JSON.parse(res);
        if (jsonData.HEADER.status === 200) {
            $("#material_list").html("");
            $("#material_list").html(jsonData.BODY);
        }
    }).catch(error => {
        console.error(error);
    });
});

$(document).on("click", ".list-group li", function() {
    $("#material_list").html("");

    let value = $(this)
        .next()
        .val();

    if (value == null || value === "") {
        return;
    }

    $.get("/admin/material/detail/" + value, res => {
        const jsonData = JSON.parse(res);
        if (jsonData.HEADER.status === 200) {
            const material = jsonData.BODY;

            if (material.m_exp_date === null) {
                $('#checkbox_date_exp').removeClass('d-none');
            }else {
                $('#checkbox_date_exp').addClass('d-none');
            }

            if (material.m_exp_date) {
                $("#input_date_exp").prop("disabled", false);
            } else {
                $("#input_date_exp").prop("disabled", true);
            }

            $("#input_m_code").val(material.m_code);
            $("#input_m_name").val(material.m_name);
            // $('#input_type').val(material.material_type.type_name);
            $("#input_unit").val(material.material_unit.unit_name);
            // $('#input_total_material').val(material.m_total);
            // $('#input_balance').val(material.m_balance);
            $(".card").removeClass("d-none");
        }
    });
});

function clearErrors(id) {
    $("#" + id).removeClass("is-invalid");

    if (id === "input_date_exp") {
        $("#icon_date_exp").removeClass("border-danger");
    }
}

$("#btSubmit").on("click", function(e) {
    e.preventDefault();
    const form = $(this).parents("form");
    const code = $("#input_m_code").val();

    if (!code) {
        Swal.fire({
            title: "กรุณาเลือกวัสดุ!",
            icon: "warning",
            showConfirmButton: true,
            cancelButtonColor: "#f5365c"
        });

        return;
    }

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) form.submit();
    });
});
