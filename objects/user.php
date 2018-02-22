<?php
class user {
	
    const TABLE = "user";
    const TABLE_SONG = "user_song";
    // database connection and table name
    private $conn;
    private $table_columns;
 
    // object properties
    public $user_id;
    public $user_name;
    public $user_email;
    public $user_identifiant;

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

	// create user
	function create(){
	 
	    // query to insert record
	    $query = "INSERT INTO
	                " . self::TABLE . "
	            SET
	                user_name=:user_name, user_email=:user_email, user_identifiant=:user_identifiant";
	 
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->user_name=htmlspecialchars(strip_tags($this->user_name));
	    $this->user_email=htmlspecialchars(strip_tags($this->user_email));
	    $this->user_identifiant=htmlspecialchars(strip_tags($this->user_identifiant));
	    // bind values
	    $stmt->bindParam(":user_name", $this->user_name);
	    $stmt->bindParam(":user_email", $this->user_email);
	    $stmt->bindParam(":user_identifiant", $this->user_identifiant);

	    // execute query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	     
	}

	// used when filling up the update user form
	function readOne($data = array()){
	 
	 	/*
	 	foreach($data as $field => $value) {
		 	$where =
	 	}
	 	*/
	    // query to read single record
	    $query = "SELECT
	               *
	            FROM
	                " . self::TABLE . "
	            WHERE
	                user_identifiant = ?
	            LIMIT
	                0,1";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare( $query );
	 
	    // bind user_identifiant of user to be updated
	    $stmt->bindParam(1, $this->user_identifiant);
	 
	    // execute query
	    $stmt->execute();
	 
	    // get retrieved row
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    
	 	foreach($row as $field => $data) {
	 		$this->{$field} = $data;
	 	}
	}

	// update the user
	function update(){
	 
	    // update query
	    $query = "UPDATE
	                " . self::TABLE . "
	            SET
	                user_name = :user_name,
	                user_email = :user_email
	            WHERE
	                user_identifiant = :user_identifiant";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->user_name=htmlspecialchars(strip_tags($this->user_name));
	    $this->user_email=htmlspecialchars(strip_tags($this->user_email));
	    $this->user_identifiant=htmlspecialchars(strip_tags($this->user_identifiant));
	 
	    // bind new values
	    $stmt->bindParam(':user_name', $this->user_name);
	    $stmt->bindParam(':user_email', $this->user_email);
	    $stmt->bindParam(':user_identifiant', $this->user_identifiant);
	 
	    // execute the query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}

	// delete the product
	function delete(){
	 
	    // delete query
	    $query = "DELETE FROM " . self::TABLE . " WHERE user_identifiant = ?";
	 
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->user_identifiant=htmlspecialchars(strip_tags($this->user_identifiant));
	 
	    // bind id of record to delete
	    $stmt->bindParam(1, $this->user_identifiant);
	 
	    // execute query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	     
	}

	public function readFavSong(){
		$query = "SELECT * FROM " . self::TABLE_SONG . " INNER JOIN " . song::TABLE . " using (song_id) WHERE user_id = ?";
	    // prepare query
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->user_id=htmlspecialchars(strip_tags($this->user_id));

	    // bind id of record
	    $stmt->bindParam(1, $this->user_id);

	    // execute query
	    $stmt->execute();

		$num = $stmt->rowCount();
		 
		$songs=array();
		// check if more than 0 record found
		if($num>0){
		
		    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
		        $songs[] = $row;
		    }
		}

		return $songs;
	}

	public function addFavSong($song_id){
	    // sanitize
	    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
	    $song_id=htmlspecialchars(strip_tags($song_id));

		//find if already added
		$songExists = $this->isSongExistsForUser($song_id);
	    if($songExists)
	    	return true;

	    // if no entry in table, insert it
		$query = "INSERT INTO " . self::TABLE_SONG . " VALUES (:user_id, :song_id)";

	    // prepare query
	    $stmt = $this->conn->prepare($query);
	 
	    // bind values
	    $stmt->bindParam(":user_id", $this->user_id);
	    $stmt->bindParam(":song_id", $song_id);

	    // execute query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}

	public function delFavSong($song_id){

	    // sanitize
	    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
	    $song_id=htmlspecialchars(strip_tags($song_id));

		//find if already deleted
		$songExists = $this->isSongExistsForUser($song_id);
	    if(!$songExists)
	    	return true;

	    // if no entry in table, insert it
		$query = "DELETE FROM " . self::TABLE_SONG . " WHERE user_id=:user_id AND song_id=:song_id";

	    // prepare query
	    $stmt = $this->conn->prepare($query);
	 
	    // bind values
	    $stmt->bindParam(":user_id", $this->user_id);
	    $stmt->bindParam(":song_id", $song_id);

	    // execute query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}

	private function isSongExistsForUser($song_id) {
		// sanitize
	    $this->user_id=htmlspecialchars(strip_tags($this->user_id));
	    $song_id=htmlspecialchars(strip_tags($song_id));

		//find if already added
		$query_select = "SELECT * FROM " . self::TABLE_SONG . " WHERE user_id=:user_id and song_id=:song_id";

	    // prepare query
	    $select = $this->conn->prepare($query_select);
	 
	    // bind values
	    $select->bindParam(":user_id", $this->user_id);
	    $select->bindParam(":song_id", $song_id);

	    $select->execute();
	    if($select->rowCount() > 0)
	    	return true;

	    return false;
	}
}