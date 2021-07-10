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
    
  .download_options {
    z-index: 10;
    position: absolute;
    top: 45px;
    left: 50%;
    transform: translateX(-50%);
  }
  .download_option {
    padding: 5px 10px;
    background: white;
  }
  .download_arrow {
    position: absolute;
    width: 20px;
    height: 20px;
    top: -10px;
    left: 48%;
    transform: translateX(-50%);
    transform: rotate(45deg);
    background: white;
    box-shadow: 1px 1px 3px 1px grey;
  }
  .delete_popup_div i {
	font-size: 30px;
	padding: 10px;
  }
  .delete_popup_div {
	  border-radius: 50%;
	  border: 3px solid red;
	  width: 60px;
	  height: 60px;
  }
</style>
<body>
    <div class='main'>
        <?php include('layouts/topbar.php'); ?>
        <div class='container'>
            <div class="wrapper">
                <div>
                    <header>Balances</header>
                </div>
                <div class='materials_display'>
                    <div class='display_table_div'>
                        <table border='1'>
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Order ID</th>
                                    <th>Payment ID</th>
                                    <th>Balance Amount</th>
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
                            if(balances_displayed.includes(`${payment.payment_of}`)) {
                                fetched_data[payment.payment_of].payment_amount = Number(fetched_data[payment.payment_of].payment_amount) + Number(payment.payment_amount);
                            } else {
                                fetched_data[payment.payment_of] = payment;
                                balances_displayed.push(`${payment.payment_of}`);
                            }
                        });
                        displayTable();
                    }
                } catch (error) {
                    console.error(error, response);
                }
            }
        }
    })
    function displayTable() {
        var index = 0;
        for(var key in fetched_data) {
            const payment = fetched_data[key];
            document.querySelector('.payments_body').innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${payment.payment_of}</td>
                    <td>${payment.payment_id}</td>
                    <td>${Number(payment.total_payment) - Number(payment.payment_amount)}</td>
                    <td>${payment.total_payment}</td>
                    <td style='position: relative;'><button style='border: 0; background: blue; color: white; border-radius: 5px; font-weight: bold; cursor: pointer; position: relative;' onclick="smallDeletePopUp.render(event, '${payment.total_payment}', '${payment.payment_of}', '${payment.payment_amount}', '${payment.total_payment}')">Pay</button></td>
                </tr>
            `;
            index++;
        }
    }

    var delete_no = 0;
    function smallCustomPopup() {
        this.render = function (event, final_amount, payment_of, total_payment, final_payment) {
            event.stopPropagation();
            event.target.parentElement.innerHTML += `
                <div class="download_options delete_no_${delete_no}">
                <div style="position: relative">
                    <div style="position: relative; width: 400px">
                    <div class="download_arrow"></div>
                    </div>
                    <div stylw="position: relative;">
                    <div style="position: absolute; width: 400px; right: 130px;">
                        <div
                        style="
                            position: relative;
                            box-shadow: 1px 1px 0px 1px grey;
                            border-radius: 5px;
                        "
                        >
                        <div
                            style="
                            border-bottom-right-radius: 5px;
                            border-bottom-left-radius: 5px;
                            color: black;
                            "
                            class="download_option"
                        >
                            New Payment
                            <form method='POST'>
                                <div>
                                    <input type='number' placeholder='Amount' class='new_payment_amount_${delete_no}' style='width: 90%; padding: 5px; border-radius: 5px; margin: 10px;' maxlength=${Number(final_amount)}>
                                </div>
                            </form>
                            <div class='delete_options' style='text-align: center; margin: 10px auto;'>
                                <button class='btn btn-danger' onclick="smallDeletePopUp.pay(event, '${delete_no}','${payment_of}', '${total_payment}', '${final_payment}')">Pay</button>
                                <button class='btn btn-default' onclick="smallDeletePopUp.no(event, '${delete_no}')">No</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            `;
            delete_no++;
        };
        this.pay = function (event, delete_no, id, total_payment, final_payment) {
            event.preventDefault();
            console.log(id)
            const amount_paid = document.querySelector('.new_payment_amount_'+delete_no).value;
            $.ajax({
                url: 'actions/payments.php',
                type: 'POST',
                data: {
                    action: 'updatePayment',
                    id: id,
                    final_payment: final_payment,
                    amount_paid: Number(amount_paid)
                }, beforeSend: function() {
                    console.log('Paying');
                }, success: function(response) {
                    console.log(response);
                }, error: function(err) {
                    console.log(err)
                }
            })
            document.querySelector(`.delete_no_${delete_no}`).outerHTML = "";
        };
        this.no = function (event, delete_no) {
            event.preventDefault();
            document.querySelector(`.delete_no_${delete_no}`).outerHTML = "";
            return;
        };
    }

    var smallDeletePopUp = new smallCustomPopup();
</script>
</html>