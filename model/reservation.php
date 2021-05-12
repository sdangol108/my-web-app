
<?php
    include_once '../class/customer.php';
    class Reservation {
        private $conn;

        private $db_table = "Reservation";

        public $party_size;
        public $reservation_datetime;
        public $customer_id;
        public $inventory_id;
        public $created_at;
        public $reservation_count;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getAll(){
            $sqlQuery = "SELECT * FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        public function create(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                    party_size = :party_size, 
                    reservation_datetime = :reservation_datetime, 
                    customer_id = :customer_id, 
                    inventory_id = :inventory_id";
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize characters
            $this->party_size=htmlspecialchars(strip_tags($this->party_size));
            $this->reservation_datetime=htmlspecialchars(strip_tags($this->reservation_datetime));
            $this->customer_id=htmlspecialchars(strip_tags($this->customer_id));
            $this->inventory_id=htmlspecialchars(strip_tags($this->inventory_id));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
            
            //bind params
            $stmt->bindParam(":party_size", $this->party_size);
            $stmt->bindParam(":reservation_datetime", $this->reservation_datetime);
            $stmt->bindParam(":customer_id", $this->customer_id);
            $stmt->bindParam(":inventory_id", $this->inventory_id);
        
            if($stmt->execute()){
               return true;
            } else {
                var_dump($stmt->errorInfo());
            }
            return false;
        }

        public function countReservedTimeSlots($inventory_id) {
            $sqlQuery = "SELECT count(*) FROM " . $this->db_table . " where inventory_id= :id";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":id", $inventory_id);
            $stmt->execute();
            return $stmt->rowCount();
        }
    }
?>

