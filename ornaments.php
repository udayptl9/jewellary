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
                <form class='materials_add_form_html'>
                    <header>Ornaments</header>
                    <div class="inputField">
                        <select class='material_id'></select>
                    </div>
                    <div class="inputField">
                        <input type="text" class='ornament_name' placeholder='Ornament Name'>
                    </div>
                    <div class="inputField">
                        <textarea class='ornament_description' cols="30" rows="10" placeholder='Ornament Description'></textarea>
                    </div>
                    <div class="inputField">
                        <input type="text" class="ornament_weight" placeholder='Ornament Weight ( in gram )'>
                    </div>
                    <div class="inputField">
                        <input type="text" class="ornament_quantity" placeholder='Ornament Stock'>
                    </div>
                    <div>
                        <button type="submit">Add</button>
                    </div>
                </form>
                <div class='materials_display'>
                    <h3>Manage Ornaments</h3>
                    <div class='display_table_div'>
                        <table border='1'>
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Stock</th>
                                    <th>Material</th>
                                    <th>Weight (in gram)</th>
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
    function deleteOrnament(id) {
        $.ajax({
            url: 'actions/ornaments.php',
            type: 'POST',
            data: {
                action: 'deleteOrnament',
                id: id,
            }, beforeSend: function() {
                console.log('Deleteing Ornament');
            }, success: function(response) {
                console.log(response);
            }

        })
    }

    function updateOrnament(id, index) {
        const newStock = document.querySelector(`.editable_stock_${index}`).value;
        $.ajax({
            url: 'actions/ornaments.php',
            type: 'POST',
            data: {
                action: 'updateOrnament',
                ornament_id: id,
                stock: newStock
            }, beforeSend: function() {
                console.log('Updating Data');
            }, success: function(response) {
                console.log(response);
            }
        })
    }

    function getMaterialName(material_id) {
        const materialData = materialsData.filter(material => {
            return material.material_id == material_id;
        });
        return materialData[0].material_name;
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
                        materialsData.forEach(material=>{
                            document.querySelector('.material_id').innerHTML += `
                                <option value='${material.material_id}'>${material.material_name} (Rs ${material.price_per_gram} / g)</option>
                            `;
                        })
                    }
                } catch (error) {
                    console.log(error, response);
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
                                        document.querySelector('.materials_body').innerHTML += `
                                            <tr>
                                                <td>${index}</td>
                                                <td>${ornament.ornament_name}</td>
                                                <td>${ornament.ornament_description}</td>
                                                <td><input style='width: 60px; text-align: center;' type='text' class='editable_stock_${index}' value='${ornament.ornament_stock}'></td>
                                                <td>${getMaterialName(ornament.material_id)}</td>
                                                <td>${ornament.ornament_weight}</td>
                                                <td><button onclick='updateOrnament(${ornament.ornament_id}, ${index})'>Update</button> <button onclick='deleteOrnament(${ornament.ornament_id})'>Delete</button></td>
                                            </tr>
                                        `;
                                        index++;
                                    })
                                }
                            } catch (error) {
                                console.log(error, response);
                            }
                        }
                    }
                })
            }
        }
    })

    // add material submit
    document.querySelector('.materials_add_form_html').addEventListener('submit', function(event) {
        event.preventDefault();
        const ornament_name = event.target.querySelector('.ornament_name').value,
              ornament_description = event.target.querySelector('.ornament_description').value,
              ornament_weight = event.target.querySelector('.ornament_weight').value,
              material_id = event.target.querySelector('.material_id').value;
              ornament_stock = event.target.querySelector('.ornament_quantity').value;
        $.ajax({
            url: 'actions/ornaments.php',
            type: 'POST',
            data: {
                action: 'addOrnament',
                ornament_name: ornament_name,
                ornament_description: ornament_description,
                ornament_weight: ornament_weight,
                material_id: material_id,
                ornament_stock: ornament_stock,
            }, beforeSend: function() {
                console.log('Adding Ornament');
            }, success: function(response, status) {
                console.log(status, response);
            }
        })
    })
</script>
</html>