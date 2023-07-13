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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $name = $_POST['name'];
    $programStudi = $_POST['program_studi'];
    $noHp = $_POST['no_hp'];

    $sql = "UPDATE students SET nim='$nim', name='$name', program_studi='$programStudi', no_hp='$noHp' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM students WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #1da1f2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0c87b8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Student</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>NIM:</label>
                <input type="text" name="nim" value="<?php echo $row['nim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Program Studi:</label>
                <input type="text" name="program_studi" value="<?php echo $row['program_studi']; ?>" required>
            </div>
            <div class="form-group">
                <label>No HP:</label>
                <input type="text" name="no_hp" value="<?php echo $row['no_hp']; ?>" required>
            </div>
            <input type="submit" value="Update">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
