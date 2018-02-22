<?php
class song {
	
    CONST TABLE = "song";

    // database connection and table name
    private $conn;
 
    // object properties
    public $song_id;
    public $song_name;
    public $song_duration;
 
    // constructor with $db as database connection
    public function __construct($db, $data = array()){
        $this->conn = $db->conn;
        $this->table_columns = $db->show_columns(self::TABLE);

        foreach(array_keys($this->table_columns) as $column){
            $this->{$column} = (isset($data[$column]))? $data[$column] : '';    
        }
    }

    // read user
    function read(){
        // select all query
        $query = "SELECT
                    *
                FROM
                    " . self::TABLE;
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    // used when filling up the update user form
    function readOne($data = array()){

        // query to read single record
        $query = "SELECT
                   *
                FROM
                    " . self::TABLE . "
                WHERE
                    song_id = ?
                LIMIT
                    0,1";
     
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind user_identifiant of user to be updated
        $stmt->bindParam(1, $this->song_id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach($row as $field => $data) {
            $this->{$field} = $data;
        }
    }
}