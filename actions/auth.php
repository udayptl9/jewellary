<?php
    require_once 'database.php';
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        if($action == 'register') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "SELECT * FROM users WHERE `username` = '$username'";
            $result = mysqli_query($conn, $sql);
            $total = mysqli_num_rows($result);
            if($total > 0) {
                print_r(json_encode(array('response'=>'User Already Existed')));
            } else {
                $sql = "INSERT INTO `users`(`username`, `password`) VALUES ('$username','$hashed_password')";
                $response = mysqli_query($conn, $sql);
                print_r(json_encode(array('response'=>$response)));
            }
        } else if($action == "login") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM users WHERE `username` = '$username'";
            $result = mysqli_query($conn, $sql);
            $total = mysqli_num_rows($result);
            if($total > 0) {
                $data = [];
                while($row = mysqli_fetch_array($result)) {
                    array_push($data, $row);
                }
                if(password_verify($password, $data[0]['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $username;
                    print_r(json_encode(array('response'=>true)));
                } else {
                    print_r(json_encode(array('response'=>false)));
                }
            } else {
                $response = json_encode(array('response' => false));
                print_r($response);
            }
            
        } else if($action == "resetPassword") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM users WHERE `username` = '$username'";
            $result = mysqli_query($conn, $sql);
            $total = mysqli_num_rows($result);
            if($total <= 0) {
                $data = [];
                while($row = mysqli_fetch_array($result)) {
                    array_push($data, $row);
                }
                $response = json_encode(array('status' => false, "No user found with username: $username"));
                print_r($response);
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE `users` SET `password`='$hashed_password' WHERE `username` = '$username'";
                $result = mysqli_query($conn, $sql);
                print_r(json_encode(array('status'=>$result)));
            }
        }
    }
?>