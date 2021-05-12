<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/Reservation.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Reservation($db);

    $stmt = $items->getReservation();
    $itemCount = $stmt->rowCount();
    //echo json_encode($itemCount)."\n";

    if($itemCount > 0){
        $reservationArr = array();
        $reservationArr["body"] = array();
        $reservationArr["itemCount"] = $itemCount;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "inventory_id" => "inventory_id",
                "party_size" => $party_size,
                "reservation_datetime" => $reservation_datetime,
                "created_at" => $created_at,
                "customer_id" => $customer_id
            );
            array_push($reservationArr["body"], $e);
        }
        echo json_encode($reservationArr)."\n";
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No reservations found.")
        );
    }