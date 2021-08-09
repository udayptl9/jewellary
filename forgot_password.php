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
                    <header>Forgot Password</header>
                    <div class="inputField">
                        <input type="text" class='username' placeholder='Username' required>
                    </div>
                    <div class="inputField">
                        <input type="password" class='password' placeholder='New Password' required>
                    </div>
                    <div>
                        <button type="submit">Reset Password</button>
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
                action: 'resetPassword',
                username: username,
                password: password
            }, beforeSend: function() {
                console.log('Resetting Password..');
            }, success: function(response) {
                console.log(response);
                try {
                    const data = JSON.parse(response).status;
                    if(data) {
                        window.location.href = './login.php'
                    } else {
                        alert("User doesnot exists with this username");
                        document.querySelector('.materials_add_form_html').reset();
                    }
                } catch (err) {
                    console.log("Please try again later");
                        document.querySelector('.materials_add_form_html').reset();

                }
            }
        })
    })
</script>
</html>
