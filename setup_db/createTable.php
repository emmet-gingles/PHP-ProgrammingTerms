<?php
// this file creates the tables if they dont already exist

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

// connect to MySQL server using file parameters
$conn = new mysqli($server, $username, $password, $database);
if($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}

// create the Topics table
$sql1 = "CREATE TABLE IF NOT EXISTS topics (
id INT AUTO_INCREMENT PRIMARY KEY,
topic VARCHAR(255) NOT NULL,
description TEXT NOT NULL
)";

if($conn -> query($sql1)) {
    echo "Table Topics created successfully"."</br>";
}
else {
    echo "Error creating table Topics " . $conn -> error;
}

// create the Tags table
$sql2 = "CREATE TABLE IF NOT EXISTS tags (
tagId INT AUTO_INCREMENT PRIMARY KEY,
tag VARCHAR(255) NOT NULL,
topicId INT,
FOREIGN KEY (topicId) REFERENCES topics(id)
)";

if($conn -> query($sql2)) {
    echo "Table Tags created successfully"."</br>";
}
else {
    echo "Error creating table Tags " . $conn -> error;
}

// close MySQL connection
$conn -> close();

?>