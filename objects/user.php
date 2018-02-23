<?php
class user {
	
    const TABLE = "user";
    const TABLE_SONG = "user_song";

    // database connection
    private $db;
    private $table_columns;
 
    // object properties
    public $user_id;
    public $user_name;
    public $user_email;

    // constructor with $db as database connection
    public function __construct($db, $data = array()){
        $this->db = $db;
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
	    $stmt = $this->db->conn->prepare($query);
	 
	    // execute query
	    $stmt->execute();
	 
	    return $stmt;
	}

	// used when filling up the update user form
	function readOne(){
	    // query to read single record
	    $query = "SELECT
	               *
	            FROM
	                " . self::TABLE . "
	            WHERE
	                user_id = ?
	            LIMIT
	                0,1";
	 
	    // prepare query statement
	    $stmt = $this->db->conn->prepare( $query );
	 
	    // bind user_id of user to be updated
	    $stmt->bindParam(1, $this->user_id);
	 
	    // execute query
	    $stmt->execute();
		$num = $stmt->rowCount();
	 	
	 	if($num != 1) {
	 		throw new Exception("Invalid number of user found : $num");
	 	}
	    // get retrieved row
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    
	 	foreach($row as $field => $data) {
	 		$this->{$field} = $data;
	 	}	
	}

	public function readFavSong(){
		//check if user is real
		$user = new user($this->db, array('user_id' => $this->user_id));
		$user->readOne();
		if($user->user_id != $this->user_id) {
			throw new Exception("Incorrect user.");
		}

		$query = "SELECT " . song::TABLE . ".* FROM " . self::TABLE_SONG . " INNER JOIN " . song::TABLE . " using (song_id) WHERE user_id = ?";
	    // prepare query
	    $stmt = $this->db->conn->prepare($query);
	 
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

		//check if user is real
		$user = new user($this->db, array('user_id' => $this->user_id));
		$user->readOne();
		if($user->user_id != $this->user_id) {
			throw new Exception("Incorrect user.");
		}

		//check if song is real
		$song = new song($this->db, array('song_id' => $song_id));
		$song->readOne();
		if($song->song_id != $song_id) {
			throw new Exception("Incorrect song.");
		}

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
	    $stmt = $this->db->conn->prepare($query);
	 
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

		//check if user is real
		$user = new user($this->db, array('user_id' => $this->user_id));
		$user->readOne();
		if($user->user_id != $this->user_id) {
			throw new Exception("Incorrect user.");
		}

		//check if song is real
		$song = new song($this->db, array('song_id' => $song_id));
		$song->readOne();
		if($song->song_id != $song_id) {
			throw new Exception("Incorrect song.");
		}

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
	    $stmt = $this->db->conn->prepare($query);
	 
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
	    $select = $this->db->conn->prepare($query_select);
	 
	    // bind values
	    $select->bindParam(":user_id", $this->user_id);
	    $select->bindParam(":song_id", $song_id);

	    $select->execute();
	    if($select->rowCount() > 0)
	    	return true;

	    return false;
	}
}