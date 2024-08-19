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

// Ambil data dari POST
$kelembapan_tanah = isset($_POST['kelembapan_tanah']) ? $_POST['kelembapan_tanah'] : '';
$kedalaman_air = isset($_POST['kedalaman_air']) ? $_POST['kedalaman_air'] : '';
$pompa_air = isset($_POST['pompa_air']) ? $_POST['pompa_air'] : '';
$gerbang_air = isset($_POST['gerbang_air']) ? $_POST['gerbang_air'] : '';

// Masukkan data ke tabel
$sql = "INSERT INTO tabel_data (kelembapan_tanah, kedalaman_air, pompa_air, gerbang_air) VALUES ('$kelembapan_tanah', '$kedalaman_air', '$pompa_air', '$gerbang_air')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
