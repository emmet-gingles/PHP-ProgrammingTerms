<?php
// this file inserts a new topic into the topics table

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

if(isset($_POST["data"])){
    // decode the JSON data from AJAX to get the values of the fields
    $json_data = json_decode($_POST['data'], false);
    $topic = $json_data -> topic;
    $description = $json_data -> description;

    // connect to MySQL database using file parameters
    $conn = new mysqli($server, $username, $password, $database);
    if($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // insert the values into the table using a prepared statement
    $sql = "INSERT INTO topics (topic, description) VALUES(?,?)";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("ss", $topic, $description);
    if($stmt -> execute()){
        echo "Topic added to database";
    }
    $stmt -> close();

    // close MySQL connection
    $conn -> close();
}

?>