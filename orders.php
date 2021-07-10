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
    .orders {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
    .order_selected {
        padding: 5px;
        border-radius: 10px;
        background: lightblue;
        min-width: 100px;
        margin: 7px;
    }
    .remove_order_selected {
        padding: 5px;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 0;
        width: 25px;
        text-align: center;
        height: 25px;
        cursor: pointer;
    }
    .remove_order_selected:hover {
        background: rgb(126, 186, 206);
    }
    .ornament_form input {
        padding: 5px;
        outline: none;
        border-radius: 4px;
        border: 0;
    }
    .ornament_form div {
        margin: 5px;
    }
    .save_ornaments {
        padding: 8px;
        border-radius: 5px;
        background: green;
        display: inline-block;
        margin: 5px 0px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        display: none;
    }
</style>
<body>
    <div class='main'>
        <?php include('layouts/topbar.php'); ?>
        <div class='container'>
            <div class="wrapper">
                <input type="text" class='order_search' placeholder='Search Orders Here...'>
                <form class='order_form_html'>
                    <header>Orders</header>
                    <div class="inputField">
                        <select class='ornament_id'>
                            <option value="notaoption">Select the Ornament</option>
                        </select>
                    </div>
                    <div>
                        <div class='orders'></div>
                        <div class='save_ornaments' onclick='fillMainForm(event)'>Save</div>
                    </div>
                    <div class="inputField">
                        <input type="text" class='customer_name' placeholder='Customer Name'>
                    </div>
                    <div class="inputField">
                        <input type="number" class='weight' placeholder='Total Weight ( in gram )'>
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
                                        <th>Items</th>
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
    var selectedOrders = [];
    document.querySelector('.ornament_id').addEventListener('change', function(event) {
        event.preventDefault();
        const newValue = event.target.value;
        if(newValue != "notaoption") {
            if(selectedOrders.includes(Number(newValue))) {
                alert('Already selected');
                return; 
            }
            selectedOrders.push(Number(newValue));
            var element = document.createElement('div');
            element.style.display = 'inline-block';
            element.classList.add('order_selected');
            element.innerHTML = `
                <div style='display: inline-block; position: relative; width: 100%;'>
                    <div class='order_ornament_id' style='display: none;'>${newValue}</div>
                    <div style='position: absolute; top: 10px; right: 0px;' onclick='remove_setected_order(event)' class='remove_order_selected'>X</div>
                    <div class='ornament_form'>
                        <form method='POST'>
                            <div>
                                <label>Ornament Name</label>
                                <input type='text' value='${getOrnamentName(Number(newValue))}' disabled>
                            </div>
                            <div>
                                <label>Ornament Weight</label>
                                <input type='number' value='${getOrnamentObject(Number(newValue)).ornament_weight}' class='ornament_weight'>
                            </div>
                            <div>
                                <label>Ornament Quantity</label>
                                <input type='number' value='1' class='ornament_quantity'>
                            </div>
                            <div>
                                <label>Ornament Price Per Gram</label>
                                <input type='number' value='${getMaterialObject(getOrnamentObject(Number(newValue)).material_id).price_per_gram}' disabled class='ornament_price_per_gram'>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.querySelector('.orders').append(element);
            event.target.selectedIndex = 0;
        }
        if(selectedOrders.length > 0) {
            document.querySelector('.save_ornaments').style.display = 'inline-block';
        } else {
            document.querySelector('.save_ornaments').style.display = 'none';
        }
    })

    document.querySelector('.order_search').addEventListener('keyup', (event)=>{
        event.preventDefault();
        var input, filter, ul, li, a, i, txtValue;
        input = event.target;
        filter = input.value.toUpperCase();
        if(filter.length > 0) {
            console.log(filter)
            document.querySelector('.order_form_html').style.display = 'none';
        } else {
            document.querySelector('.order_form_html').style.display = 'block';
        }
        ul = document.querySelector(".materials_body");
        li = ul.getElementsByTagName("tr");
        for (i = 0; i < li.length; i++) {
            a = li[i].querySelector(".search_type");
            txtValue = a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    })

    function remove_setected_order(event) {
        event.preventDefault();
        const index = selectedOrders.indexOf(Number(event.target.parentElement.querySelector('.order_ornament_id').innerHTML));
        if (index > -1) {
            selectedOrders.splice(index, 1);
        }
        event.target.parentElement.parentElement.outerHTML = '';
        if(selectedOrders.length > 0) {
            document.querySelector('.save_ornaments').style.display = 'inline-block';
        } else {
            document.querySelector('.save_ornaments').style.display = 'none';
        }
        if(selectedOrders.length == 0) {
            document.querySelector('.weight').value = 0;
            document.querySelector('.final_amount').value = 0;
        }
    }

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

    function getMaterialObject(material_id) {
        const materialData = materialsData.filter(material => {
            return material.material_id == material_id;
        });
        return materialData[0];
    }

    function getMaterialName(material_id) {
        const materialData = materialsData.filter(material => {
            return material.material_id == material_id;
        });
        return materialData[0].material_name;
    }

    function getOrnamentObject(ornament_id) {
        const ornamentData = ornamentsData.filter(ornament => {
            return ornament.ornament_id == ornament_id;
        });
        return ornamentData[0];
    }

    function fillMainForm(event) {
        event.preventDefault();
        var total_weight = 0;
        var total_final_amount = 0;
        document.querySelectorAll('.order_selected').forEach(order_selected=>{
            total_weight += Number(order_selected.querySelector('.ornament_weight').value) * Number(order_selected.querySelector('.ornament_quantity').value);
            total_final_amount += Number(order_selected.querySelector('.ornament_weight').value) * Number(order_selected.querySelector('.ornament_quantity').value) * Number(order_selected.querySelector('.ornament_price_per_gram').value);
        })
        document.querySelector('.final_amount').value = total_final_amount;
        document.querySelector('.weight').value = total_weight;
    }

    function getOrnamentName(ornament_id) {
        try {
            const ornamentData = ornamentsData.filter(ornament => {
                return ornament.ornament_id == ornament_id;
            });
            return ornamentData[0].ornament_name;
        } catch {
            const ornament_ids = ornament_id.split(' ');
            var ornament_names = '';
            ornament_ids.forEach((ornament, index)=>{
                if(ornament.length > 0) {
                    const ornamentData = ornamentsData.filter(ornamentObject => {
                        return ornamentObject.ornament_id == ornament;
                    });
                    if(ornament_ids.length > index+2) {
                        ornament_names += `${ornamentData[0].ornament_name}, `;
                    } else {
                        ornament_names += `${ornamentData[0].ornament_name}`;
                    }
                }
            })
            return ornament_names;
        }
    }

    function editProgress(progress_id, order_id) {
        $.ajax({
            url: 'actions/orders.php',
            type: 'POST',
            data: {
                action: 'editOrderProgress',
                order_id: order_id,
                progress_id: progress_id
            }, beforeSend: function() {
                console.log('Updating Progress');
            }, success: function(response) {
                console.log(response);
            }
        })
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
                                        if(response_JSON.status) {
                                            response_JSON.data.forEach((order, index) => {
                                                const progressDiv = `
                                                    <div class='progressDiv' style='width: 150px;'>
                                                        <div class='progress editProgress' onclick='editProgress(0, ${order.order_id})' style='width: 20px; height: 20px; border-radius: 50%;'>
                                                            <div class='showlight red ${(order.progress == '0')?('activeprogress'):('')}'></div>
                                                            <div class='progressdesc' style='width: 300px;'>
                                                                Ornament not available with me, Need to order
                                                            </div>
                                                            <div class='progress_code' style='display: none;'>0</div>
                                                        </div>
                                                        <div class='progress editProgress' onclick='editProgress(1, ${order.order_id})' style='width: 20px; height: 20px; border-radius: 50%;'>
                                                            <div class='showlight orange ${(order.progress == '1')?('activeprogress'):('')}'></div>
                                                            <div class='progressdesc' style='width: 100px;'>
                                                                Processing
                                                            </div>
                                                            <div class='progress_code' style='display: none;'>1</div>
                                                        </div>
                                                        <div class='progress editProgress' onclick='editProgress(2, ${order.order_id})' style='width: 20px; height: 20px; border-radius: 50%;'>
                                                            <div class='showlight yellow ${(order.progress == '2')?('activeprogress'):('')}'></div>
                                                            <div class='progressdesc' style='width: 250px;'>
                                                                Ready but, not yet delivered
                                                            </div>
                                                            <div class='progress_code' style='display: none;'>2</div>
                                                        </div>
                                                        <div class='progress editProgress' onclick='editProgress(3, ${order.order_id})' style='width: 20px; height: 20px; border-radius: 50%;'>
                                                            <div class='showlight green ${(order.progress == '3')?('activeprogress'):('')}'></div>
                                                            <div class='progressdesc'>
                                                                Delivered
                                                            </div>
                                                            <div class='progress_code' style='display: none;'>3</div>
                                                        </div>
                                                    </div>
                                                `;
                                                document.querySelector('.materials_body').innerHTML += `
                                                    <tr>
                                                        <td>${index+1}</td>
                                                        <td>${order.order_key}</td>
                                                        <td class='search_type'>${order.customer_name}</td>
                                                        <td>${order.address}</td>
                                                        <td>${order.delivery_date}</td>
                                                        <td>${getOrnamentName(order.ornament_id)}</td>
                                                        <td>${order.weight}</td>
                                                        <td style='position: relative;' class='editable_tr'><div ${(Number(order.amount_paid) < Number(order.final_amount))?('style="color: red;"'):('')}>${order.amount_paid}</div><div class='edit_amount'>Edit</div></td>
                                                        <td>${order.final_amount}</td>
                                                        <td><div>${progressDiv}<div class='reference_id' style='display: none;'>${order.order_id}</div></div></td>
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
        var ornament_ids = '';
        document.querySelectorAll('.order_ornament_id').forEach(id=>{
            ornament_ids += `${Number(id.innerHTML)} `;
        })
        if(ornament_ids.length == 0) {
            alert('Please Select atleast one ornament');
            return;
        }
         console.log(ornament_ids);
        try {
            const progress_id = event.target.querySelector('.activeprogress').parentElement.querySelector('.progress_code').innerHTML,
                  ornament_id = ornament_ids;
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