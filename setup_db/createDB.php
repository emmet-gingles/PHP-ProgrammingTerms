<?php
// this file creates the database if it does not already exist

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

// connect to MySQL server using file parameters
$conn = new mysqli($server, $username, $password);
if($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}

// create the database provided it does not already exist
$create_db = "CREATE DATABASE IF NOT EXISTS $database";
if($conn -> query($create_db)) {
    echo "Database $database created successfully"."</br>";
}
else {
    echo "Error creating database " . $conn -> error;
}

// close MySQL connection
$conn -> close();

?>