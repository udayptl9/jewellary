<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <?php
        include('imports.php');
    ?>
</head>
<body>
    <style>
        .container {
            padding: 20px;
        }
        .graph_div {
            padding: 10px;
            border: 0;
            border-radius: 7px;
            background: white;
            margin-top: 5px;
        }
    </style>
    <div class='main'>
        <?php include('layouts/topbar.php'); ?>
        <div class='container'>
            <h3>Payments Analytics</h3>
            <div class="graph_div">
                
            </div>
        </div>
    </div>
</body>
<script src="assets/apexcharts.js"></script>
<script>
    var balances_displayed = [];
    var fetched_data = [];
    $.ajax({
        url: 'actions/payments.php',
        type: 'POST',
        data: {
            action: 'getPayments'
        }, beforeSend: function() {
            console.log("Getting Payments");
        }, success: function(response, status) {
            if(status == 'success') {
                try {
                    const response_JSON = JSON.parse(response);
                    if(response_JSON.status) {
                        response_JSON.data.map((payment, index) => {
                            if(balances_displayed.includes(`${payment.payment_on}`)) {
                                fetched_data[payment.payment_on].payment_amount = Number(fetched_data[payment.payment_on].payment_amount) + Number(payment.payment_amount);
                            } else {
                                fetched_data[payment.payment_on] = {'payment_amount': Number(payment.payment_amount)};
                                balances_displayed.push(`${payment.payment_on}`);
                            }
                        });
                        displayChart();
                    }
                } catch (error) {
                    console.error(error, response);
                }
            }
        }
    })

    function displayChart() {
        var dataToDisplaay = {'dates': [], 'payments': []}
        for(var key in fetched_data) {
            dataToDisplaay['dates'].push(key);
            dataToDisplaay['payments'].push(fetched_data[key]['payment_amount']);
        }
        var options = {
            series: [{
                name: "Payments",
                data: dataToDisplaay['payments'],
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false,
                },
                toolbar:{
                    export: {
                        csv: {
                            headerValue: 'Date',
                            headerCategory: 'Date',
                            filename: 'Payments_Data'
                        },
                        svg: {
                            filename: 'Payments_Data'
                        },
                        png: {
                            filename: 'Payments_Data'
                        }
                    }
                }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Payments',
            align: 'left'
        },
        grid: {
            row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
            },
        },
        xaxis: {
            categories: dataToDisplaay['dates'],
        }
        };

        var chart = new ApexCharts(document.querySelector(".graph_div"), options);
        chart.render();
    }
    
</script>
</html>
