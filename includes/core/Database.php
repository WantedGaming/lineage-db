<?php
class Database {
    private $connection;
    private static $instance;
    
    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            die("Database connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8");
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function getLastId() {
        return $this->connection->insert_id;
    }
    
    // Method to handle pagination
    public function paginate($sql, $page = 1, $perPage = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $perPage;
        
        // Count total rows for pagination
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as counted";
        $result = $this->query($countSql);
        $row = $result->fetch_assoc();
        $total = $row['total'];
        
        // Get paginated results
        $paginatedSql = $sql . " LIMIT $offset, $perPage";
        $result = $this->query($paginatedSql);
        
        return [
            'data' => $result,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }
}
?>
