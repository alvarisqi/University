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

// Delete grades associated with the student
$deleteGradesSql = "DELETE FROM grades WHERE student_id='$id'";
if ($conn->query($deleteGradesSql) === FALSE) {
    echo "Error deleting grades: " . $conn->error;
    exit();
}

// Delete student from database
$deleteStudentSql = "DELETE FROM students WHERE id='$id'";
if ($conn->query($deleteStudentSql) === TRUE) {
    header('Location: index.php');
} else {
    echo "Error deleting student: " . $conn->error;
}

$conn->close();
?>
