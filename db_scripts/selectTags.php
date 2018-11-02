<?php
// this file returns a list of tags that begin with some user input

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

if(isset($_POST["text"])){
    // get the AJAX data
    $text = $_POST["text"];

    // connect to MySQL database using file parameters
    $conn = new mysqli($server, $username, $password, $database);
    if($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    // return unique tags that begin with the variable value
    $sql = "SELECT DISTINCT tag FROM tags WHERE tag LIKE '$text%'";
    if($conn -> query($sql)){
        $results = $conn -> query($sql);
        if($results -> num_rows > 0 ) {
            // in case of multiple results, we seperate them using a break tag
            while ($row = $results->fetch_assoc()) {
                echo $row["tag"] . " <br/>";
            }
        }
    }

    // close MySQL connection
    $conn -> close();
}


?>