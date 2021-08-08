<?php
    require_once 'database.php';
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        if($action == 'getPayments') {
            $sql = "SELECT * FROM payments";
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
        } else if($action == 'deletePayment') {
            $id = $_POST['id'];
            $sql = "DELETE FROM `payments` WHERE `payment_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        } else if($action == 'updatePayment') {
            $id = $_POST['id'];
            $amount_paid = $_POST['amount_paid'];
            $final_payment = $_POST['final_payment'];
            $payment_on = date('d/m/Y');
            $sql = "INSERT INTO `payments`(`payment_of`, `payment_amount`, `total_payment`, `payment_on`) VALUES ('$id' ,'$amount_paid','$final_payment' ,'$payment_on')";
            $result = mysqli_query($conn, $sql);
            $response = json_encode(array('status'=> $result));
            print_r($response);
        }
    }
?>