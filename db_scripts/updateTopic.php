<?php
// this file updates a particular topic

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

if(isset($_POST["data"])){
    // decode the JSON data from AJAX to get the values of the fields
    $json_data = json_decode($_POST['data'], false);
    $id = intval($json_data -> id);
    $topic = $json_data -> topic;
    $description = $json_data -> description;

    // connect to MySQL database using file parameters
    $conn = new mysqli($server, $username, $password, $database);
    if($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // update the record using a prepared statement
    $sql = "UPDATE topics SET topic = ?, description = ? WHERE id = ?";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("ssd", $topic, $description, $id);
    if($stmt -> execute()) {
        if($stmt -> affected_rows > 0) {
            echo "Topic updated";
        }
    }
    $stmt -> close();

    // close MySQL connection
    $conn -> close();

}

?>