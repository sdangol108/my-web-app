<?php 
    class Database {
        private $host = "localhost:3307";
        private $database_name = "test";
        private $username = "root";
        private $password = "Saahas28";
        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                // echo "Database is connected\n";
            } catch(PDOException $exception) {
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>