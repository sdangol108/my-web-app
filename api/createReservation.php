<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../class/inventory.php';
    include_once '../class/Reservation.php';
    include_once '../class/customer.php';


    $database = new Database();
    $db = $database->getConnection();
    $inventory = new Inventory($db);
    $cust = new Customer($db);
    $reservation = new Reservation($db);
    

    $data = json_decode(file_get_contents("php://input"));
    var_dump($data);
 

    try {
        $cust->first_name = $data->first_name;
        $cust->last_name = $data->last_name;
        $cust->email = $data->email;
        if ($cust->findCustomer()) {
            echo "customer found";
            $cust->id = $cust->findCustomerId();
        } else {
            echo "no customer exist";
            if ($cust->createCustomer()) {
                $cust->id = $cust->findCustomerId();
            } else {
                echo "customer not created";
            }
        }
        $inventory->id = $inventory->selectInventoryId($data->reservation_datetime);
        var_dump($inventory->id);
        // setReservation($data, $cust->id, $inventory->id);
    } catch(Exception $e) {
        echo $e;

    }

    function setReservation($data, $customer, $inventory, $reservation) {
        $reservation->party_size = $data->party_size;
        $reservation->reservation_datetime = $data->reservation_datetime;
        $reservation->inventory_id = $inventory->id;
        $reservation->customer_id = $customer->id;
        $reservation->created_at = date('Y-m-d H:i:s');
    }
    
    
    
    // if ($item->createInventory()){
    //     echo 'Inventory created successfully.';
    // } else{
    //     echo 'Inventory was not created.';
    // }
?>