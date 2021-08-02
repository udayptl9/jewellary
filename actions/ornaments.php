<?php
    require_once 'database.php';
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        if($action == 'getOrnaments') {
            $sql = "SELECT * FROM ornaments";
            $result = mysqli_query($conn, $sql);
            $response = '';
            $total = mysqli_num_rows($result);
            if($total > 0) {
                $data = [];
                while($row = mysqli_fetch_array($result)) {
                    array_push($data, $row);
                }
                $response = array('status'=> true, 'results'=> $total, 'data'=> $data);
            } else {
                $response = array('status'=> true, 'results'=> 0, 'data'=> []);
            }
            print_r(json_encode($response));
        } else if($action == 'addOrnament') {
            $ornament_name = $_POST['ornament_name'];
            $ornament_weight = $_POST['ornament_weight'];
            $ornament_description = $_POST['ornament_description'];
            $material_id = $_POST['material_id'];
            $ornament_stock = $_POST['ornament_stock'];
            $sql = "INSERT INTO `ornaments`(`material_id`, `ornament_name`, `ornament_description`, `ornament_weight`, `ornament_stock`) VALUES ('$material_id','$ornament_name','$ornament_description', '$ornament_weight', '$ornament_stock')";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=> $result, 'data' => [$ornament_name, $ornament_weight], 'error' => mysqli_error($conn));
            print_r(json_encode($response));
        } else if($action == 'deleteOrnament') {
            $id = $_POST['id'];
            $sql = "DELETE FROM `ornaments` WHERE `ornament_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        } else if($action == 'updateOrnament') {
            $ornament_id = $_POST['ornament_id'];
            $stock = $_POST['stock'];
            $sql = "UPDATE `ornaments` SET `ornament_stock`='$stock' WHERE `ornament_id` = $ornament_id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$ornament_id);
            print_r(json_encode($response));
        } else if($action == 'balanceOrderCount') {
            $sqlPayment = "SELECT payment_of, payment_amount, total_payment FROM payments";
            $sqlOrder = "SELECT * FROM orders WHERE `progress` <> '3'";
            $resultPayment = mysqli_query($conn, $sqlPayment);
            $total = mysqli_num_rows($resultPayment);
            $data = [];
            if($total > 0) {
                while($row = mysqli_fetch_array($resultPayment)) {
                    array_push($data, $row);
                }
            }
            $resultOrder = mysqli_query($conn, $sqlOrder);
            $response = array('status'=>$resultPayment, 'payments'=>$data, 'orderCount'=>mysqli_num_rows($resultOrder));
            print_r(json_encode($response));
        }
    }
?>