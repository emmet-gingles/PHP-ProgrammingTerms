<?php
// this file inserts some initial data into both tables

// use file to establish MySQL connection parameters
require_once "../db/connection.php";

// connect to MySQL database using file parameters
$conn = new mysqli($server, $username, $password, $database);
if($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}

// array of topics to be inserted
$topics = array(
    array("Object Oriented Programming", "Object Oriented Programming or OOP is a style of programming that involves creating instances of classes. An example of an OOP language is Java."),
    array("Class", "Classes are a set of objects that share common characteristics, eg. cat and dog are instances of the Animal class."),
    array("Constructor", "A constructor is a method or function that is run when an instance of a class is created. A constructor has the same name as the class and does not return a value. A constructor is normally used to set the default value of variables."),
    array("Function", "A function, also called a method is a task that an instance of a class can perform. A function that returns the value of a variable is called an accessor while a function that updates the value of a variable is called a setter."),
    array("Variable", "A variable is a location capable of storing temporary data within a program. In OOP variables are used to store data that describe a class, eg. for a Person class the variables could be name and age."),
    array("Data type", "A data type is a classification of what kind of data a variable can hold. In Java the data type must be defined when creating a variable. Some of the most common data types are strings, integers, floats and booleans."),
    array("String", 'A string is a set of alphanumeric characters. String are enclosed in quotation marks. An example of a string is "Hello World".'),
    array("Integer", "An integer is a positive or negative whole number. Examples of positive integers are 1,2,3 while examples of negative integers are -1,-2,-3. 0 is also an integer even though it is neither positive or negative."),
    array("Float", "A float is a data type used to store numeric variables that contain a decimal point that can float rather than being in a fixed position within the number. Examples of floats are 1.23, 34.256 and 1365.2."),
    array("Boolean", "A boolean is a data type where the variable is either true or false. Booleans are commonly used in if statements to decide whether or not to execute a segment of code."),
    array("Array", "An array is a group of related data values or objects that are grouped together. Each element of an array has a unique index which can be used to access that element's data. An array can only store a fixed number of objects that must be defined when it is created."),
    array("Vector", "A vector, also called a list is similar to an array but it does not have a defined size meaning you can insert as many objects as necessary. "),
    array("Java", "Java is one of the most popular programming language used for creating software applications. It follows the OOP principals where objects are created from classes."),
    array("JavaScript", "JavaScript is a client-side scripting language that is used to add functionality or animation to HTML pages. JavaScript functionality includes an event run when a button is clicked eg. form submission or outputting some text to the screen."),
    array("HTML", "HTML (HyperText Markup Language) is a basic programming language which controls what a webpage displays. It is comprised of various tags which enclose text displayed on the page."),
    array("PHP", "PHP (PHP Hypertext Preprocessor) is a server-side programming language. It is used for creating dynamic web pages that interact with databases. "),
    array("Python", "Python is a server-side programming language that uses an interpreter to determine how it runs code. Python is usually run from the command line."),
    array("SQL", "SQL (Structured Query Language) is a programming language used for database CRUD (Create, Read, Update, Delete) operations. In SQL all data is stored in tables within a database. Tables must be defined before data can be added to them.")
);

// array of tags to be inserted
$tags = array(
    array("OOP", 1),
    array("OOP", 2),
    array("OOP", 3),
    array("OOP", 4),
    array("OOP", 5),
    array("Data types", 6),
    array("Data types", 7),
    array("Data types", 8),
    array("Data types", 9),
    array("Data types", 10),
    array("Object storage", 11),
    array("Object storage", 12),
    array("Language", 13),
    array("Language", 14),
    array("Language", 15),
    array("Language", 16),
    array("Language", 17),
    array("Language", 18),
    array("Client-side", 14),
    array("Server-side", 16),
    array("Server-side", 17),
    array("Database", 18)
);

// insert each topic into the the table using a prepared statement
$insert = "INSERT INTO topics (topic, description) VALUES(?,?)";
$stmt = $conn -> prepare($insert);
$stmt -> bind_param("ss", $topic, $description);

foreach($topics as $t){
    $topic = $t[0];
    $description = $t[1];
    if(!$stmt -> execute()) {
        $stmt -> error;
    }
}
echo sizeof($topics) . " records inserted into Topics". "<br>";
$stmt -> close();

// insert each tag into the table using a prepared statement
$insert = "INSERT INTO tags (tag, topicId) VALUES(?,?)";
$stmt = $conn -> prepare($insert);
$stmt -> bind_param("sd", $tag, $topicId);

foreach($tags as $t){
    $tag = $t[0];
    $topicId = $t[1];
    if(!$stmt -> execute()) {
        $stmt -> error;
    }
}
echo sizeof($tags) . " records inserted into Tags";
$stmt -> close();

// close MySQL connection
$conn -> close();

?>