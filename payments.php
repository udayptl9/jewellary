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
<style>
    td, th {
        border-collapse: collapse;
        padding: 5px;
    }
</style>
<body>
    <div class='main'>
        <?php include('layouts/topbar.php'); ?>
        <div class='container'>
            <div class="wrapper">
                <div>
                    <header>Payments</header>
                </div>
                <div class='materials_display'>
                    <div class='display_table_div'>
                        <table border='1'>
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Order ID</th>
                                    <th>Payment ID</th>
                                    <th>Amount Paid</th>
                                    <th>Paid On</th>
                                    <th>Final Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class='payments_body'>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</body>
<script>
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
                        response_JSON.data = response_JSON.data.reverse()
                        response_JSON.data.map((payment, index) => {
                            document.querySelector('.payments_body').innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${payment.payment_of}</td>
                                    <td>${payment.payment_id}</td>
                                    <td>${payment.payment_amount}</td>
                                    <td>${payment.payment_on}</td>
                                    <td>${payment.total_payment}</td>
                                    <td><button style='border: 0; background: red; color: white; border-radius: 5px; font-weight: bold; cursor: pointer;' onclick="delete_payment(event, '${payment.payment_id}')">Delete</button></td>
                                </tr>
                            `;
                        });
                    }
                } catch (error) {
                    console.error(error, response);
                }
            }
        }
    })
    function delete_payment(event, id) {
        event.preventDefault();
        $.ajax({
            url: 'actions/payments.php',
            type: 'POST',
            data: {
                action: 'deletePayment',
                id: id,
            }, beforeSend: function() {
                console.log('Deleteing Payment');
            }, success: function(response) {
                console.log(response);
            }, error: function(err) {
                console.log(err)
            }
        })
    }
</script>
</html>