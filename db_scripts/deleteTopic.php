<?php
// this file deletes a topic from the database

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

if(isset($_POST["id"])){
    // decode the JSON data from AJAX to get the values of the id
    $id = intval($_POST["id"]);

    // connect to MySQL database using file parameters
    $conn = new mysqli($server, $username, $password, $database);
    if($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // delete tag from table using prepared statement
    $sql = "DELETE FROM topics WHERE id = ?";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("d", $id);
    if($stmt -> execute()) {
        if($stmt -> affected_rows > 0) {
            echo "Topic deleted";
        }
    }
    $stmt -> close();

    // close MySQL connection
    $conn -> close();
}

?>