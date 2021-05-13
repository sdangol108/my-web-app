<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../model/inventory.php';
    include_once '../model/Reservation.php';
    include_once '../model/customer.php';


    $database = new Database();
    $db = $database->getConnection();
    $inventory = new Inventory($db);
    $customer = new Customer($db);
    $reservation = new Reservation($db);

    $data = json_decode(file_get_contents("php://input"));
    // var_dump($data);
    $message = "";
    try {
        check_customer($customer, $data);
        $inventory->id = $inventory->selectId($data->reservation_datetime);
        $reservationArr = array();

        if ($inventory->reservation_nos > 0 && $inventory->reservation_nos < $reservation->countReservedTimeSlots($inventory->id)) {
            setReservation($data, $customer, $inventory, $reservation);
            if ($reservation->create()) {
                $reservationArr["status"] = "200";
                $reservationArr["message"] = 'Reservations done successfully.';
                echo json_encode($reservationArr)."\n";
            } else {
                $reservationArr["status"] = "500";
                $reservationArr["message"] = 'Encountered with some errors';
                echo json_encode($reservationArr)."\n";
            }
        } elseif (empty($inventory->reservation_nos)) {  //reservations are zero or null
            $reservationArr["status"] = "500";
            $reservationArr["message"] = 'Reservations are unavailable at this time.';
            echo json_encode($reservationArr)."\n";
        } else {
            $reservationArr["status"] = "500";
            $reservationArr["message"] = 'Reservations full at this time.';
            echo json_encode($reservationArr)."\n";
        }
    } catch(Exception $e) {
        $reservationArr["status"] = "500";
        $reservationArr["message"] = 'Encountered with some errors';
        echo json_encode($reservationArr)."\n";
    }

    function setReservation($data, $customer, $inventory, $reservation) {
        $reservation->party_size = $data->party_size;
        $reservation->reservation_datetime = $data->reservation_datetime;
        $reservation->inventory_id = $inventory->id;
        $reservation->customer_id = $customer->id;
        $reservation->created_at = date('Y-m-d H:i:s');
    }

    function check_customer($customer, $data) {
        $customer->first_name = $data->first_name;
        $customer->last_name = $data->last_name;
        $customer->email = $data->email;
        if ($customer->findCustomer()) {
            // echo "customer found";
            $customer->id = $customer->findId();
        } else {
            // echo "no customer exist";
            if ($customer->create()) {
                $customer->id = $customer->findId();
            } else {
                // echo "customer not created";
            }
        }
    }
?>