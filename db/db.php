<?php
echo 'sss';$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "excel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
//    echo "Connected successfully";
}
