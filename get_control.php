<?php

// db config -----------------------------------------------
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abc_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//----------------------------------------------------------

$sql = "SELECT pompa_air, gerbang_air, auto FROM control ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of the row
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["pompa_air" => 0, "gerbang_air" => 0, "auto" => 0]); // Default values if no results
}
$conn->close();
