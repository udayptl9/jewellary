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
        <div class="topnav navbar">
            <a href="index.php">Home</a>
            <a href="materials.php">Materials</a>
            <a href="ornaments.php">Ornaments</a>
            <a href="orders.php">Orders</a>
        </div>
        <div class='container'>
            <div class="wrapper">
                <form class='order_form_html'>
                    <header>Orders</header>
                    <div class="inputField">
                        <select class='ornament_id'></select>
                    </div>
                    <div class="inputField">
                        <input type="text" class='customer_name' placeholder='Customer Name'>
                    </div>
                    <div class="inputField">
                        <input type="number" class='weight' placeholder='Ornament Weight ( in gram )'>
                    </div>
                    <div class="inputField">
                        <input type="date" class='delivery_date'>
                    </div>
                    <div class="inputField">
                        <textarea class='address' cols="30" rows="10" placeholder='Ornament Address'></textarea>
                    </div>
                    <div class="inputField">
                        <input type="number" class="amount_paid" placeholder='Amount Paid'>
                    </div>
                    <div class="inputField">
                        <input type="number" class="final_amount" placeholder='Final Amount'>
                    </div>
                    <div class="inputField">
                        <div class='progressDiv'>
                            <div class='progress'>
                                <div class='light red activeprogress'></div>
                                <div class='progressdesc' style='width: 300px;'>
                                    Ornament not available with me, Need to order
                                </div>
                                <div class='progress_code' style='display: none;'>0</div>
                            </div>
                            <div class='progress'>
                                <div class='light orange'></div>
                                <div class='progressdesc' style='width: 100px;'>
                                    Processing
                                </div>
                                <div class='progress_code' style='display: none;'>1</div>
                            </div>
                            <div class='progress'>
                                <div class='light yellow'></div>
                                <div class='progressdesc' style='width: 250px;'>
                                    Ready but, not yet delivered
                                </div>
                                <div class='progress_code' style='display: none;'>2</div>
                            </div>
                            <div class='progress'>
                                <div class='light green'></div>
                                <div class='progressdesc'>
                                    Delivered
                                </div>
                                <div class='progress_code' style='display: none;'>3</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit">Add</button>
                    </div>
                </form>
                <div class='materials_display'>
                        <h3>Manage Orders</h3>
                        <div class='display_table_div'>
                            <table border='1'>
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>Order ID</th>
                                        <th>Customer Name</th>
                                        <th>Address</th>
                                        <th>Delivery Date</th>
                                        <th>Item Name</th>
                                        <th>Weight (in gram)</th>
                                        <th>Amount Paid</th>
                                        <th>Final Amount</th>
                                        <th>Progress</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class='materials_body'>

                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>  
        </div>
    </div>
</body>
<script>
    var materialsData = [];
    var ornamentsData = [];
    document.querySelectorAll('.progress').forEach(progress=>{
        progress.addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelectorAll('.light').forEach(light=>{
                light.classList.remove('activeprogress');
            })
            event.target.querySelector('.light').classList.add('activeprogress');
        })
    })
    function deleteOrder(event, id) {
        event.preventDefault();
        console.log(event.target);
        $.ajax({
            url: 'actions/orders.php',
            type: 'POST',
            data: {
                action: 'deleteOrder',
                id: id,
            }, beforeSend: function() {
                console.log('Deleteing Order');
            }, success: function(response) {
                console.log(response);
            }

        })
    }

    function updateOrnament(id, index) {
        document.querySelector('.materials_body').querySelectorAll('tr')[index-1].querySelectorAll('td[class="editable"]').forEach(td=> {
            td.innerHTML = `<input type='text' value=${td.innerHTML}>`;
        })
    }

    function getMaterialName(material_id) {
        const materialData = materialsData.filter(material => {
            return material.material_id == material_id;
        });
        return materialData[0].material_name;
    }

    function getOrnamentName(ornament_id) {
        const ornamentData = ornamentsData.filter(ornament => {
            return ornament.ornament_id == ornament_id;
        });
        return ornamentData[0].ornament_name;
    }

    $.ajax({
        url: 'actions/materials.php',
        type: 'POST',
        data: {
            action: 'getMaterials'
        }, beforeSend: function() {
            console.log("Getting Materials");
        }, success: function(response, status) {
            if(status == 'success') {
                try {
                    const response_JSON = JSON.parse(response);
                    if(response_JSON.status) {
                        materialsData = response_JSON.data;
                    }
                } catch (error) {
                    console.error(error, response);
                }
                $.ajax({
                    url: 'actions/ornaments.php',
                    type: 'POST',
                    data: {
                        action: 'getOrnaments'
                    }, beforeSend: function() {
                        console.log("Getting Ornaments");
                    }, success: function(response, status) {
                        if(status == 'success') {
                            try {
                                const response_JSON = JSON.parse(response);
                                if(response_JSON.status) {
                                    let index = 1;
                                    ornamentsData = response_JSON.data;
                                    ornamentsData.forEach(ornament=>{
                                        document.querySelector('.ornament_id').innerHTML += `
                                            <option value='${ornament.ornament_id}'>${ornament.ornament_name} (${getMaterialName(ornament.material_id)})</option>
                                        `;
                                        index++;
                                    })
                                }
                            } catch (error) {
                                console.log(error, response);
                            }
                        }
                        // display orders
                        $.ajax({
                            url: 'actions/orders.php',
                            type: 'POST',
                            data: {
                                action: 'getOrders'
                            }, beforeSend: function() {
                                console.log("Getting Orders");
                            }, success: function(response, status) {
                                if(status == 'success') {
                                    try {
                                        const response_JSON = JSON.parse(response);
                                        const progress_colors = ['red', 'orange', 'yellow', 'green'];
                                        if(response_JSON.status) {
                                            response_JSON.data.forEach((order, index) => {
                                                const color = progress_colors[order.progress];
                                                document.querySelector('.materials_body').innerHTML += `
                                                    <tr>
                                                        <td>${index+1}</td>
                                                        <td>${order.order_id}</td>
                                                        <td>${order.customer_name}</td>
                                                        <td>${order.address}</td>
                                                        <td>${order.delivery_date}</td>
                                                        <td>${getOrnamentName(order.ornament_id)}</td>
                                                        <td>${order.weight}</td>
                                                        <td>${order.amount_paid}</td>
                                                        <td>${order.final_amount}</td>
                                                        <td><div style='background: ${color}; width: 25px; height: 25px; border-radius: 50%; margin: 0 auto;'></div></td>
                                                        <td><button onclick='deleteOrder(event, "${order.order_id}")'>Delete</button></td>
                                                    </tr>
                                                `;
                                            })
                                        }
                                    } catch (error) {
                                        console.error(error, response);
                                    }
                                    
                                }
                            }
                        })
                    }
                })
            }
        }
    })

    // add order submit
    document.querySelector('.order_form_html').addEventListener('submit', function(event) {
        event.preventDefault();
        try {
            const progress_id = event.target.querySelector('.activeprogress').parentElement.querySelector('.progress_code').innerHTML,
                  ornament_id = event.target.querySelector('.ornament_id').value,
                  customer_name = event.target.querySelector('.customer_name').value,
                  weight = event.target.querySelector('.weight').value,
                  delivery_date = event.target.querySelector('.delivery_date').value,
                  address = event.target.querySelector('.address').value,
                  amount_paid = event.target.querySelector('.amount_paid').value,
                  final_amount = event.target.querySelector('.final_amount').value;
                  $.ajax({
                    url: 'actions/orders.php',
                    type: 'POST',
                    data: {
                        action: 'addOrder',
                        progress_id: progress_id,
                        ornament_id: ornament_id,
                        customer_name: customer_name,
                        weight: weight,
                        delivery_date: delivery_date,
                        address: address,
                        amount_paid: amount_paid,
                        final_amount: final_amount,
                    }, beforeSend: function() {
                        console.log('Adding Order');
                    }, success: function(response, status) {
                        console.log(status, response);
                    }
                })
        } catch (error) {
            console.log(error)
        }
    })
</script>
</html>