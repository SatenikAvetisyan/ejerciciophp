<?php

$servername = "db";
$username = "myuser";
$password = "mypassword";
$mydatabase="mydatabase";


$conn = new mysqli($servername, $username, $password,$mydatabase);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>