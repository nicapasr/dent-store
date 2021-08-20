$(() => {
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
    });

    var $canvasStockIn = $('#chart_stock_in');
    var chartStockIn = new Chart($canvasStockIn, {
        type: 'bar',
        options: {
            responsive: true,
        }
    });

    var $canvasStockOut = $('#chart_stock_out');
    var chartStockOut = new Chart($canvasStockOut, {
        type: 'bar',
        options: {
            responsive: true,
        }
    });

    $('#btn_submit').on('click', function (e) {
        e.preventDefault();

        $('input[name=from]').removeClass('is-invalid');
        $('input[name=to]').removeClass('is-invalid');

        const from = $('input[name=from]').val();
        const to = $('input[name=to]').val();

        const formData = {
            from: from,
            to: to
        }

        $.get("/reports/by/date", formData, (res) => {
            // console.log(res);
            if(res.status == 200) {
                if(res.data.stock_in.length > 0) {
                    let labels = [];
                    let sums = [];

                    for (let i = 0; i < res.data.stock_in.length; i++){
                        labels.push(res.data.stock_in[i].balances.materials.m_name + ' (' + res.data.stock_in[i].balances.materials.material_unit.unit_name + ')');
                        sums.push(res.data.stock_in[i].sum);
                    }

                    const datasets = [{
                        label: 'จำนวนวัสดุที่นำเข้า',
                        data: sums
                    }]

                    updateChart(chartStockIn, labels, datasets)
                }else {
                    updateChart(chartStockIn, [], [])
                }

                if (res.data.stock_out.length > 0) {
                    let labels = [];
                    let sums = [];

                    for (let i = 0; i < res.data.stock_out.length; i++) {
                        labels.push(res.data.stock_out[i].balances.materials.m_name + ' (' + res.data.stock_out[i].balances.materials.material_unit.unit_name + ')');
                        sums.push(res.data.stock_out[i].sum);
                    }

                    const datasets = [{
                        label: 'จำนวนวัสดุที่เบิก',
                        data: sums
                    }]

                    updateChart(chartStockOut, labels, datasets)
                } else {
                    updateChart(chartStockOut, [], [])
                }
                $('#div_grap').removeClass('d-none');
            }
        });

        $('#btn_download_excel_in').on('click', function (e) {
            e.preventDefault();
            let from = $('input[name=from]').val();
            let to = $('input[name=to]').val();

            $('#label_excel_from').val(from);
            $('#label_excel_to').val(to);

            // console.log($('input[name=from]').val())
            $('#download_excel_stock_in').submit();
        });

        $('#btn_download_excel_out').on('click', function (e) {
            e.preventDefault();
            let from = $('input[name=from]').val();
            let to = $('input[name=to]').val();

            $('#label_excel_from_out').val(from);
            $('#label_excel_to_out').val(to);

            // console.log($('input[name=from]').val())
            $('#download_excel_stock_out').submit();
        });
    });

    function updateChart(chart, labels, datasets) {
        chart.data.labels = labels
        chart.data.datasets = datasets
        chart.update();
    }
});
