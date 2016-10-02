<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mySqlHelper
 *
 * @author Maty
 */
class mySqlHelper {

private $servername;
private $username;
private $password;
private $dbName;
private $conn;

function __construct($host, $user, $pass, $db){
    $this->servername = $host;
    $this->username = $user;
    $this->password = $pass;
    $this->dbName = $db;
}

// Create connection
private function openConn(){
    $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbName);
    // Check connection
    if ($this->conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
}// Cierra OpenConn

function insertLine($sql){
    $this->OpenConn();
    if ($this->conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $this->conn->error;
    }
    $this->closeConn();
}

private function closeConn(){
    $this->conn->close;
}

}
?>