<?php
    require_once 'database.php';
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        // get all materials
        if($action == 'getMaterials') {
            $sql = "SELECT * FROM materials";
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
        } else if($action == 'addMaterial') {
            // add material
            $material_name = $_POST['material_name'];
            $price_per_gram = $_POST['price_per_gram'];
            $sql = "INSERT INTO `materials`(`material_name`, `price_per_gram`) VALUES ('$material_name','$price_per_gram')";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=> $result, 'data' => [$material_name, $price_per_gram]);
            print_r(json_encode($response));
        } else if($action == 'deleteMaterial') {
            // delete material
            $id = $_POST['id'];
            $sql = "DELETE FROM `materials` WHERE `material_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        } else if($action == 'updateMaterial') {
            // update material
            $id = $_POST['id'];
            $price_per_gram = $_POST['price_per_gram'];
            $sql = "UPDATE `materials` SET `price_per_gram`='$price_per_gram' WHERE `material_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        }
    }
?>