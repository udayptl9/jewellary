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
            margin-top: 5px;
        }
        .container_graph_div {
            display: grid;
            grid-template-columns: 80% 20%;
            border: 0;
            border-radius: 7px;
            background: white;
        }
        .counts_div {
            display: grid;
            padding: 10px;
            grid-template-rows: 50% 50%;
            border: 0;
            border-radius: 7px;
            background: white;
            text-align: center;
        }
        .counts_div .count_div {
            position: relative;
            color: black;
            border-radius: 7px;
        }
        .count_div:hover {
            transform: scale(1.03);
            box-shadow: 1px 1px 3px 1px grey;
        }
        .float_div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <div class='main'>
        <?php include('layouts/topbar.php'); ?>
        <div class='container'>
            <div class='container_graph_div'>
                <div class="graph_div"></div>
                <div class="counts_div">
                    <a href='orders.php' class='count_div'>
                        <div class='float_div'>
                            <h3>Orders</h3>
                            <span class='orders_count'>0</span>
                        </div>
                    </a>
                    <a href='balances.php' class='count_div'>
                        <div class='float_div'>
                            <h3>Balances</h3>
                            <span class='balances_count'>0</span>
                        </div>
                    </a>
                </div>
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
    $.ajax({
        url: 'actions/ornaments.php',
        type: 'POST',
        data: {
            action: 'balanceOrderCount'
        }, beforeSend: function() {
            console.log("Getting Balances Orders Counts");
        }, success: function(response, status) {
            try {
                const response_JSON = JSON.parse(response);
                console.log(response_JSON);
                document.querySelector('.orders_count').innerHTML = response_JSON.orderCount;
                var balances_count = 0;
                var balances_displayed = [];
                var fetched_data = [];
                response_JSON.payments.map((payment, index) => {
                    if(balances_displayed.includes(`${payment.payment_of}`)) {
                        fetched_data[payment.payment_of].payment_amount = Number(fetched_data[payment.payment_of].payment_amount) + Number(payment.payment_amount);
                    } else {
                        fetched_data[payment.payment_of] = payment;
                        balances_displayed.push(`${payment.payment_of}`);
                    }
                });
                for(var key in fetched_data) {
                    if(Number(fetched_data[key].total_payment) > Number(fetched_data[key].payment_amount)) {
                        balances_count++;
                    }
                }
                document.querySelector('.balances_count').innerHTML = balances_count;
            } catch (err) {
                console.log(err);
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
