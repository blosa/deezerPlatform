<?php
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "deezer";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct(){

        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
    }

    public function show_columns($table) {

        // show columns
        $query = "SHOW COLUMNS FROM " . $table;
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        $columns_arr=array();
     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $columns_arr[$row['Field']] = $row;
        }
     
        return $columns_arr;
    }
}