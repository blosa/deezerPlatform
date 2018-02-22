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
 
    // get the database connection
    /*
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
    */

//htmlspecialchars(strip_tags($this->user_name))
    public static function where($aWhere, $sCond = 'AND')
    {
        $sWhere = '';
        foreach ($aWhere as $sField => $aData) {
            if (!empty($sWhere)) {
                $sWhere .= ' '.$sCond.' ';
            }

            if (is_numeric($sField)) {
                $sWhere .= $aData;
                continue;
            }

            if (!is_array($aData)) {
                $sData = htmlspecialchars(strip_tags($aData)); // or mysqli escape string
            } else {
                $aEscapedData = array();
                foreach ($aData as $sFieldData) {
                    $aEscapedData[] = htmlspecialchars(strip_tags($sFieldData));
                }
                $sData = implode('\',\'', $aEscapedData);
            }

            $sWhere .= $sField.' in (\''.$sData.'\')';
        }
        return $sWhere; //  arg1 in ('value1', 'value2'...) AND ...
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