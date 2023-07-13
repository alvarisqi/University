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
    $nim = $_POST['nim'];
    $name = $_POST['name'];
    $programStudy = $_POST['program_studi'];
    $phoneNumber = $_POST['no_hp'];

    // Check if the NIM already exists
    $checkQuery = "SELECT * FROM students WHERE nim = '$nim'";
    $checkResult = $conn->query($checkQuery);
    if ($checkResult->num_rows > 0) {
        echo "Error: Student with NIM '$nim' already exists.";
    } else {
        // Insert the student data into the database
        $sql = "INSERT INTO students (nim, name, program_studi, no_hp) VALUES ('$nim', '$name', '$programStudy', '$phoneNumber')";

        if ($conn->query($sql) === TRUE) {
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Mahasiswa Baru</title>
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
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
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
        <h2>Mahasiswa Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>NIM:</label>
                <input type="text" name="nim" required class="form-control">
            </div>
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="name" required class="form-control">
            </div>
            <div class="form-group">
                <label>Program Studi:</label>
                <input type="text" name="program_studi" required class="form-control">
            </div>
            <div class="form-group">
                <label>Nomor Telepon:</label>
                <input type="text" name="no_hp" required class="form-control">
            </div>
            <input type="submit" value="Add" class="btn btn-primary">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
