<?php
session_start();

// Check if the user is logged in as admin
function checkAdminSession()
{
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit();
    }
}

// Panggil fungsi checkAdminSession()
checkAdminSession();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Delete the course from the database
$sql = "DELETE FROM courses WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    header('Location: list_courses.php');
    exit();
} else {
    echo "Error deleting course: " . $conn->error;
}

$conn->close();
?>
