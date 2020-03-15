<?php

class DbOperations {

    private $conn;

    function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "bca");
        if ($this->conn == false) {
            die("Couldn't connect to database" . mysqli_connect_error());
        }
    }

    function getConnObj() {
        return $this->conn;
    }

    // execute query function
    // returns the result if its a SELECT query
    // else return true or false for other queries
    function execute($query) {
        if ($query == "") {
            die("Can't execute query without any sql query!");
            return false;
        }
        
        if (explode(" ", $query)[0] == "SELECT") {
            if ($res = mysqli_query($this->conn, $query))
                return $res;
            else {
                die("Couldn't execute query: " . mysqli_error($this->conn));
                return false;
            }
        } else {
            if (mysqli_query($this->conn, $query))
                return true;
            else {
                die("Couldn't execute query: " . mysqli_error($this->conn));
                return false;
            }
        }
    }

    // check if a data query exists in the database table
    // returns true if exists else false
    function exists($query) {    
        if ($res = mysqli_query($this->conn, $query)) {
            if (mysqli_num_rows($res) > 0) {
                return True;
            } else {
                return False;
            }
        } else {
            die("Couldn't execute query: ". mysqli_error($this->conn));
            return False;
        }
    }
}
?>