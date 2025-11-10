<?php
// Singleton for db connection 
class DatabaseConnection {
    private static $instance = null;
    private $connection; 
    private function __construct() {
        $config = [
            'host' => 'localhost',
            'dbname' => 'test_db',
            'username' => 'root',
            'password' => ''
        ];
        
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Connection error: " . $e->getMessage());
        }
    }   
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // No clone
    private function __clone() {}
    
    // No unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Usage
try {
    $db1 = DatabaseConnection::getInstance();
    $db2 = DatabaseConnection::getInstance();
    
    // Check for uniq
    var_dump($db1 === $db2); // bool(true)
    
    // Query example
    $conn = $db1->getConnection();
    $stmt = $conn->query("SELECT * FROM users LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    echo $e->getMessage();
}