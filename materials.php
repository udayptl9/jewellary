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
        <div class='navbar'>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="materials.php">Materials</a></li>
                    <li><a href="ornaments.php">Ornaments</a></li>
                </ul>
            </nav>
        </div>
        <div class='container'>
            <div class='materials_main'>
                <div class='materials_add_form'>
                    <h4>Add Materials</h4>
                    <form class='materials_add_form_html'>
                        <div>
                            <label>Material Name</label>
                            <input type="text" class='material_name'>
                        </div>
                        <div>
                            <label>Price Per Gram</label>
                            <input type="number" class='material_price_per_gram'>
                        </div>
                        <div>
                            <input type="submit" value="Add Material">
                        </div>
                    </form>
                </div>
                <div class='materials_display'>
                    <h3>Materials</h3>
                    <div class='display_table_div'>
                        <table border='1'>
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Material Name</th>
                                    <th>Price Per Gram</th>
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
    function deleteMaterial(id) {
        $.ajax({
            url: 'actions/materials.php',
            type: 'POST',
            data: {
                action: 'deleteMaterial',
                id: id,
            }, beforeSend: function() {
                console.log('Deleteing Material');
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
                if(status == 'success') {
                    try {
                        const response_JSON = JSON.parse(response);
                        if(response_JSON.status) {
                            let index = 1;
                            response_JSON.data.forEach(material=>{
                                document.querySelector('.materials_body').innerHTML += `
                                    <tr>
                                        <td>${index}</td>
                                        <td>${material.material_name}</td>
                                        <td>${material.price_per_gram}</td>
                                        <td><button onclick='deleteMaterial(${material.material_id})'>Update</button> <button onclick='deleteMaterial(${material.material_id})'>Delete</button></td>
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
        }
    })

    // add material submit
    document.querySelector('.materials_add_form_html').addEventListener('submit', function(event) {
        event.preventDefault();
        const material_name = event.target.querySelector('.material_name').value,
              price_per_gram = event.target.querySelector('.material_price_per_gram').value;
        $.ajax({
            url: 'actions/materials.php',
            type: 'POST',
            data: {
                action: 'addMaterial',
                material_name: material_name,
                price_per_gram: price_per_gram,
            }, beforeSend: function() {
                console.log('Adding Material');
            }, success: function(response, status) {
                console.log(response);
            }
        })
    })
</script>
</html>