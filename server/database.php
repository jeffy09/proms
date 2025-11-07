<?php
class Database {
    private $host = "localhost"; 
    private $db_name = "Your_DB_Name_Here";
    private $username = "Your_Username_Here"; 
    private $password = "Your_Password_Here";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    public function secureQuery($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        return $stmt;
    }
}
?>
