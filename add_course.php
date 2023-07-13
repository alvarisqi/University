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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    // Check if the course with the same code already exists
    $checkSql = "SELECT * FROM courses WHERE kode='$kode'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows > 0) {
        echo "<script>
            alert('Error: The course with the same code already exists.');
            document.getElementsByName('kode')[0].style.borderColor = 'red';
            window.location.href = 'add_course.php';
        </script>";
        exit();
    }

    $sql = "INSERT INTO courses (kode, nama) VALUES ('$kode', '$nama')";

    if ($conn->query($sql) === TRUE) {
        header('Location: list_courses.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Add Course</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f1f1f1;
                margin: 0;
                padding: 20px;
            }

            .container {
                max-width: 500px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
                text-align: center;
            }

            label {
                font-weight: bold;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 3px;
                margin-bottom: 10px;
            }

            input[type="submit"] {
                padding: 10px 20px;
                background-color: #1da1f2;
                color: #fff;
                border: none;
                border-radius: 3px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Add Course</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="kode">Kode:</label>
                    <input type="text" id="kode" name="kode" required>
                </div>
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <input type="submit" value="Add Course" class="btn btn-primary">
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>

</html>

