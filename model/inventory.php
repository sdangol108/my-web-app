
<?php
    class Inventory {
        private $conn;

        private $db_table = "Inventory";

        public $id;
        public $start_time;
        public $end_time;
        public $reservation_nos;
        public $created_at;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getAll(){
            $sqlQuery = "SELECT * FROM " . $this->db_table . "
                INNER JOIN Reservation on inventory.id = reservation.inventory_id WHERE
                reservation.reservation_datetime BETWEEN :start_date and :end_date";
            $sqlQuery = "SELECT * FROM " . $this->db_table . "
                INNER JOIN Reservation on inventory.id = reservation.inventory_id";
            $stmt = $this->conn->prepare($sqlQuery);

            $this->start_time = htmlspecialchars(strip_tags($this->start_date));
            $this->end_time = htmlspecialchars(strip_tags($this->end_date));

            $stmt->bindParam(":start_date", $this->start_time);
            $stmt->bindParam(":end_date", $this->end_time);
            if($stmt->execute()){
                return $stmt;
             } else {
                var_dump($stmt->errorInfo());
             }
            return false;
        }

        public function create(){
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
            } else {
               var_dump($stmt->errorInfo());
            }
            return false;
        }

        public function selectId($reservation_time) {
            $sqlQuery = "SELECT * from    
                            ". $this->db_table ."  where
                        start_time <= :start_time and end_time >= :end_time";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":start_time", $reservation_time);
            $stmt->bindParam(":end_time", $reservation_time);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $this->reservation_nos = $result[0]['reservation_nos'];
                var_dump($result[0]['id']);
                return $result[0]['id'];
             } else {
                var_dump($stmt->errorInfo());
             }
             return false;
        }
    }
?>

