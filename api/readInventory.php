<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    include_once '../config/database.php';
    include_once '../model/inventory.php';

    $database = new Database();
    $db = $database->getConnection();

    $inventory = new Inventory($db);

    $stmt = $inventory->getAll();
    $itemCount = $stmt->rowCount();
    echo $stmt->fetch(PDO::FETCH_ASSOC);
    echo $_SERVER['REQUEST_METHOD'];
    echo $_SERVER['HTTP_ORIGIN'];


    if($itemCount > 0){
        $inventoryArr = array();
        $inventoryArr["body"] = array();
        $inventoryArr["itemCount"] = $itemCount;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "start_time" => $start_time,
                "end_time" => $end_time,
                "reservation_nos" => $reservation_nos,
                "age" => $created_at
            );
            array_push($inventoryArr["body"], $e);
        }
        echo json_encode($inventoryArr)."\n";
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }