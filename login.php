<?php
    session_start();
    unset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div class='container'>
            <div class="wrapper">
                <form class='materials_add_form_html'>
                    <header>Login</header>
                    <div class="inputField">
                        <input type="text" class='username' placeholder='Username'>
                    </div>
                    <div class="inputField">
                        <input type="password" class='password' placeholder='Password'>
                    </div>
                    <div>
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    document.querySelector('.materials_add_form_html').addEventListener('submit', (event)=>{
        event.preventDefault();
        const username = document.querySelector('.username').value,
              password = document.querySelector('.password').value;
        $.ajax({
            url: 'actions/auth.php',
            type: 'POST',
            data: {
                action: 'login',
                username: username,
                password: password
            }, beforeSend: function() {
                console.log('Logging In..');
            }, success: function(response) {
                try {
                    const data = JSON.parse(response).response;
                    if(data) {
                        window.location.href = './index.php'
                    } else {
                        alert("Username and Password does not match");
                    }
                } catch (err) {
                    console.log("Please try again later");
                }
            }
        })
    })
</script>
</html>
