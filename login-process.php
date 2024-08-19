<?php
session_start();

// db config -------------------------------------------------
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

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql_query = "SELECT name, email, username, id FROM users 
                  WHERE username='$username' AND password='$password'
                  LIMIT 1"; //sql query

    $result = $conn->query($sql_query);

    if ($result->num_rows > 0) {
        // data valid
        while ($row = $result->fetch_assoc()) {
            $_SESSION['name'] = $row["name"];

            // $conn->close();
            header('location:main_pages/index.php'); // redirect to main page
            exit();
        }
    } else {
        // data invalid
        echo "<script language=\"javascript\">alert(\"Invalid username or password\");
        document.location.href='index.php?error_login';</script>";
        exit();
    }
} else {
    header('location:index.php');
    exit();
}

// Setelah proses autentikasi, tambahkan kode untuk menampilkan tabel data
// Pastikan untuk mengatur sesi dan koneksi database sesuai kebutuhan aplikasi.

// Example of displaying table data from 'tabel_data'
$sql_query_data = "SELECT * FROM tabel_data";
$result_data = $conn->query($sql_query_data);
