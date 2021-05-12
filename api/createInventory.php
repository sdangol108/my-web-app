<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../model/inventory.php';

    $database = new Database();
    $db = $database->getConnection();
    $inventory = new Inventory($db);

    $data = json_decode(file_get_contents("php://input"));
    $inventory->start_time = $data->start_time;
    $inventory->end_time = $data->end_time;
    $inventory->reservation_nos = $data->reservation_nos;
    $inventory->created_at = date('Y-m-d H:i:s');
    $inventoryArr = array();
    if($inventory->create()){
        $inventoryArr["status"] = "200";
        $inventoryArr["message"] = 'Inventory created successfully.';
        echo json_encode($inventoryArr)."\n";
    } else{
        $inventoryArr["status"] = "500";
        $inventoryArr["message"] = 'Inventory was not created.';
        echo json_encode($inventoryArr)."\n";
    }
?>