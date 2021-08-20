$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
    }); //กำหนดเป็นวันปัจุบัน
});

$('#checkbox_m_exp_date').on('click', () => {
    let isChecked = $('#checkbox_m_exp_date')[0].checked
    if (isChecked) {
        $('#input_m_exp_date').prop("disabled", false)
    } else {
        $('#input_m_exp_date').val('')
        $('#input_m_exp_date').prop("disabled", true)
    }
});

function clearErrors(id) {
    $('#' + id).removeClass('is-invalid');
}

function calTotalPrice() {
    document.getElementById('input_total_price').value = document.getElementById('input_price_unit').value * document
        .getElementById('input_value_in').value;
}

function calMaterial() {
    calTotalPrice()
    if ($.trim($('#input_value_in').val()) != '') {
        document.getElementById('input_total_price').value = $('#input_value_in').val();
    } else {
        document.getElementById('input_total_price').value = 0;
    }
}

$('#btSubmit').on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents('form');

    Swal.fire({
        title: 'กรุณาตรวจสอบข้อมูล!!',
        text: 'ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก',
        icon: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: '#f5365c'
    }).then(function (confirm) {
        if (confirm.value) form.submit();
    });
});
