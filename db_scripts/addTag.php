<?php
// this file inserts a new tag into the tag table with reference to a topic

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

if(isset($_POST["data"])){
    // decode the JSON data from AJAX to get the values of the fields
    $json_data = json_decode($_POST['data'], false);
    $id = intval($json_data -> id);
    $tag = $json_data -> tag;

    // connect to MySQL database using file parameters
    $conn = new mysqli($server, $username, $password, $database);
    if($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // before adding the tag, make sure it is not already associated with the topic
    $sql = "SELECT DISTINCT tag FROM tags WHERE topicId = '$id' AND tag = '$tag'";
    if($conn -> query($sql)){
        $results = $conn -> query($sql);
        if($results -> num_rows > 0 ) {
            echo "ERROR: Tag is already associated with topic";
        }
        else{
            // insert the values into the table using a prepared statement
            $insert = "INSERT INTO tags (tag, topicId) VALUES(?,?)";
            $stmt = $conn -> prepare($insert);
            $stmt -> bind_param("sd", $tag, $id);
            if($stmt -> execute()) {
                echo "Tag added to topic";
            }
            else{
                $stmt -> error;
            }
            $stmt -> close();
        }
    }

    // close MySQL connection
    $conn -> close();
}

?>