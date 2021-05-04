<?php
    require_once 'database.php';
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        if($action == 'getOrders') {
            $sql = "SELECT * FROM orders";
            $result = mysqli_query($conn, $sql);
            $response = '';
            $total = mysqli_num_rows($result);
            if($total > 0) {
                $data = [];
                while($row = mysqli_fetch_array($result)) {
                    array_push($data, $row);
                }
                $response = array('status' => true, 'results' => $total, 'data' => $data);
            } else {
                $response = array('status' => true, 'results' => 0, 'data' => []);
            }
            print_r(json_encode($response));
        } else if($action == 'addOrder') {
            $customer_name = $_POST['customer_name'];
            $progress_id = $_POST['progress_id'];
            $ornament_id = $_POST['ornament_id'];
            $weight = $_POST['weight'];
            $delivery_date = $_POST['delivery_date'];
            $address = $_POST['address'];
            $amount_paid = $_POST['amount_paid'];
            $final_amount = $_POST['final_amount'];
            $sql = "INSERT INTO `orders`(`customer_name`, `ornament_id`, `weight`, `delivery_date`, `address`, `amount_paid`, `final_amount`, `progress`) VALUES ('$customer_name','$ornament_id','$weight','$delivery_date','$address','$amount_paid','$final_amount','$progress_id')";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=> $result);
            print_r(json_encode($response));
        } else if($action == 'deleteOrder') {
            $id = $_POST['id'];
            $sql = "DELETE FROM `orders` WHERE `order_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        } else if($action == 'editOrderProgress') {
            $order_id = $_POST['order_id'];
            $progress_id = $_POST['progress_id'];
            $sql = "UPDATE `orders` SET `progress`='$progress_id' WHERE `order_id` = $order_id";
            $response = mysqli_query($conn, $sql);
            print_r(json_encode(array('response'=>$response, 'data'=>$order_id.$progress_id)));
        }
    }
?>