<?php
class user {
	
    // database connection and table name
    private $conn;
    private $table_name = "user";
 
    // object properties
    public $user_id;
    public $user_name;
    public $user_email;
    public $user_identifiant;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read user
	function read(){
	    // select all query
	    $query = "SELECT
	                *
	            FROM
	                " . $this->table_name;
	 
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
	                " . $this->table_name . "
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
	                " . $this->table_name . "
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
	                " . $this->table_name . "
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
	    $stmt->bindParam(':user_name', $this->name);
	    $stmt->bindParam(':user_email', $this->price);
	    $stmt->bindParam(':user_identifiant', $this->description);
	 
	    // execute the query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}

	// delete the product
	function delete(){
	 
	    // delete query
	    $query = "DELETE FROM " . $this->table_name . " WHERE user_identifiant = ?";
	 
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
}