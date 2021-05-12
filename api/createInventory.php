<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../class/inventory.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new Inventory($db);

    $data = json_decode(file_get_contents("php://input"));

    $item->start_time = $data->start_time;
    $item->end_time = $data->end_time;
    $item->reservation_nos = $data->reservation_nos;
    $item->created_at = date('Y-m-d H:i:s');
    
    if($item->createInventory()){
        echo 'Inventory created successfully.';
    } else{
        echo 'Inventory was not created.';
    }
?>