
<?php
    class Inventory{
        // Connection
        private $conn;

        // Table
        private $db_table = "Inventory";

        // Columns
        public $id;
        public $start_time;
        public $end_time;
        public $reservation_nos;
        public $created_at;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getInventory(){
            $sqlQuery = "SELECT * FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        public function createInventory(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        start_time = :start_time, 
                        end_time = :end_time, 
                        reservation_nos = :reservation_nos, 
                        created_at = :created_at";
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize characters
            $this->start_time=htmlspecialchars(strip_tags($this->start_time));
            $this->end_time=htmlspecialchars(strip_tags($this->end_time));
            $this->reservation_nos=htmlspecialchars(strip_tags($this->reservation_nos));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
        
            // bind parameters
            $stmt->bindParam(":start_time", $this->start_time);
            $stmt->bindParam(":end_time", $this->end_time);
            $stmt->bindParam(":reservation_nos", $this->reservation_nos);
            $stmt->bindParam(":created_at", $this->created_at);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        public function selectInventoryId($reservation_time) {
            $sqlQuery = "SELECT * from inventory   
                            ". $this->db_table ."  
                        start_time <= :start_time and end_time >= :end_time";
            $stmt = $this->conn->prepare($sqlQuery);
            echo $reservation_time;
            // $this->start_time=htmlspecialchars(strip_tags($this->start_time));
            // $this->end_time=htmlspecialchars(strip_tags($this->end_time));
            $stmt->bindParam(":start_time", $reservation_time);
            $stmt->bindParam(":end_time", $reservation_time);

            $stmt->execute();
            if ($stmt->rowCount() > 0) {

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                var_dump($result[0]['id']);
                return $result[0]['id'];
             } else {
                 echo 43434;
             }
             return false;
        }
    }
?>

