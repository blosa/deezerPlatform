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

	// used when filling up the update product form
	function readOne($data = null){
	 
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
	 
	    // bind user_identifiant of product to be updated
	    $stmt->bindParam(1, $this->user_identifiant);
	 
	    // execute query
	    $stmt->execute();
	 
	    // get retrieved row
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    
	 	foreach($row as $field => $data) {
	 		$this->{$field} = $data;
	 	}
	}

	// update the product
	function update(){
	 
	    // update query
	    $query = "UPDATE
	                " . $this->table_name . "
	            SET
	                name = :name,
	                price = :price,
	                description = :description,
	                category_id = :category_id
	            WHERE
	                id = :id";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->name=htmlspecialchars(strip_tags($this->name));
	    $this->price=htmlspecialchars(strip_tags($this->price));
	    $this->description=htmlspecialchars(strip_tags($this->description));
	    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
	    $this->id=htmlspecialchars(strip_tags($this->id));
	 
	    // bind new values
	    $stmt->bindParam(':name', $this->name);
	    $stmt->bindParam(':price', $this->price);
	    $stmt->bindParam(':description', $this->description);
	    $stmt->bindParam(':category_id', $this->category_id);
	    $stmt->bindParam(':id', $this->id);
	 
	    // execute the query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}
}