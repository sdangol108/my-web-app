
<?php
    class Customer {
        // Connection
        private $conn;

        // Table
        private $db_table = "customer";

        // Columns
        public $id;
        public $first_name;
        public $last_name;
        public $email;
        public $created_at;

        public function __construct($db){
            $this->conn = $db;
        }

        public function findCustomer() {
            $select_customer = "SELECT email from ". $this->db_table ." WHERE email = :email";
            $stmt = $this->conn->prepare($select_customer);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
             } 
            return false;
        }

        public function create() {
            $sqlQuery = "INSERT INTO
                    ". $this->db_table ."
                SET
                first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                created_at = :created_at";
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize characters
            $this->first_name=htmlspecialchars(strip_tags($this->first_name));
            $this->last_name=htmlspecialchars(strip_tags($this->last_name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->created_at=htmlspecialchars(strip_tags($this->created_at));
            $this->created_at= date('Y-m-d H:i:s');
            // bind parameters
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":created_at", $this->created_at);
        
            if($stmt->execute()){
                return true;
            } else {
                var_dump($stmt->errorInfo());
            }
            return false;
        }

        public function findId() {
            $select_customer_id = "SELECT id from ". $this->db_table ." WHERE email = :email";
            $stmt = $this->conn->prepare($select_customer_id);
            $this->email = htmlspecialchars(strip_tags($this->email));
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo $result;
                return $result[0]['id'];
             }
            return false;
        }
    }
?>

