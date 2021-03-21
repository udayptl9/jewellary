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
            $sql = "INSERT INTO `ornaments`(`material_id`, `ornament_name`, `ornament_description`, `ornament_weight`) VALUES ('$material_id','$ornament_name','$ornament_description', '$ornament_weight')";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=> $result, 'data' => [$ornament_name, $ornament_weight]);
            print_r(json_encode($response));
        } else if($action == 'deleteOrnament') {
            $id = $_POST['id'];
            $sql = "DELETE FROM `ornaments` WHERE `ornament_id` = $id";
            $result = mysqli_query($conn, $sql);
            $response = array('status'=>$result, 'id'=>$id);
            print_r(json_encode($response));
        }
    }
?>