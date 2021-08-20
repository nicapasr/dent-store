$(document).ready(function () {
    var $canvasStockIn = $('#chart_stock_in');
    var chartStockIn = new Chart($canvasStockIn, {
        type: 'bar',
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
        // const _token = $('meta[name="csrf-token"]').attr('content');
        const warehouse = $('#select_warehouse').val();
        const year = $('#select_year').val();

        const formData = {
            warehouse: warehouse,
            year: year,
        }

        $.get("/reports/by/warehouse", formData,
            (response) => {
                // console.log(response);
                if (response.status == 200) {
                    if (response.data.stockIn.length > 0) {
                        let labels = [];
                        let sums = [];

                        for (let i = 0; i < response.data.stockIn.length; i++) {
                            labels.push(response.data.stockIn[i].balances.materials.m_name + ' (' + response.data.stockIn[i].balances.materials.material_unit.unit_name + ')');
                            sums.push(response.data.stockIn[i].sum);
                        }

                        const datasets = [{
                            label: 'จำนวนวัสดุที่นำเข้า',
                            data: sums
                        }]

                        updateChart(chartStockIn, labels, datasets)
                    } else {
                        updateChart(chartStockIn, [], [])
                    }

                    if (response.data.stockOut.length > 0) {
                        let labels = [];
                        let sums = [];

                        for (let i = 0; i < response.data.stockOut.length; i++) {
                            labels.push(response.data.stockOut[i].m_name + ' (' + response.data.stockOut[i].unit_name + ')');
                            sums.push(response.data.stockOut[i].sum);
                        }

                        const datasets = [{
                            label: 'จำนวนวัสดุที่นำเข้า',
                            data: sums
                        }]

                        updateChart(chartStockOut, labels, datasets)
                    } else {
                        updateChart(chartStockOut, [], [])
                    }

                    $('#div_grap').removeClass('d-none');
                }
            }
        );

        $('#btn_download_excel_in').on('click', function (e) {
            e.preventDefault();
            let warehouse = $('#select_warehouse').val();
            let year = $('#select_year').val();

            $('#label_excel_warehouse').val(warehouse);
            $('#label_excel_year').val(year);

            // console.log($('input[name=from]').val())
            $('#download_excel_stock_in').submit();
        });

        $('#btn_download_excel_out').on('click', function (e) {
            e.preventDefault();
            let warehouse = $('#select_warehouse').val();
            let year = $('#select_year').val();

            $('#label_excel_warehouse_out').val(warehouse);
            $('#label_excel_year_out').val(year);

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
