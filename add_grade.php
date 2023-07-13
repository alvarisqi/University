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

// Function to determine grade based on value
function getGrade($nilai)
{
    if ($nilai >= 85) {
        return 'A';
    } elseif ($nilai >= 75 && $nilai < 85) {
        return 'B';
    } elseif ($nilai >= 65 && $nilai < 75) {
        return 'C';
    } elseif ($nilai >= 50 && $nilai < 65) {
        return 'D';
    } else {
        return 'E';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];
    $nilai = $_POST['grade'];
    $grade = getGrade($nilai); // Menggunakan fungsi getGrade() untuk mendapatkan grade nilai

    // Check if the same course has already been added for the student
    $checkSql = "SELECT * FROM grades WHERE student_id='$studentId' AND course_id='$courseId'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows > 0) {
        echo "Error: The selected course has already been added for the student.";
        exit();
    }

    $sql = "INSERT INTO grades (student_id, course_id, nilai, grade) VALUES ('$studentId', '$courseId', '$nilai', '$grade')";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM students";
$students = $conn->query($sql);

$sql = "SELECT * FROM courses";
$courses = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Add Grade</title>
    </head>

    <body>
        <h2>Add Grade</h2>
        <form action="" method="POST">
            <label>Student:</label>
            <select name="student_id" required>
                <?php
                if ($students->num_rows > 0) {
                    while ($row = $students->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            <label>Course:</label>
            <select name="course_id" required>
                <?php
                if ($courses->num_rows > 0) {
                    while ($row = $courses->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nama'] . "</option>";
                    }
                }
                ?>
            </select>
            <br><br>
            <label>Grade:</label>
            <input type="number" name="grade" required>
            <br><br>
            <input type="submit" value="Add">
        </form>
    </body>

</html>
