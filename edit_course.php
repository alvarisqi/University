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
    $courseId = $_POST['course_id'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];

    // Check if the course with the same code already exists
    $checkSql = "SELECT * FROM courses WHERE kode='$kode' AND id != '$courseId'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Error: The course with the same code already exists.')</script>";
        // Tambahkan script JavaScript untuk mengganti warna input kode
        echo "<script>document.getElementsByName('kode')[0].style.borderColor = 'red';</script>";
        exit();
    }

    $sql = "UPDATE courses SET kode='$kode', nama='$nama' WHERE id='$courseId'";

    if ($conn->query($sql) === TRUE) {
        header('Location: list_courses.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Get the course ID from the URL parameter
$courseId = $_GET['id'];

// Retrieve course data from the database
$sql = "SELECT * FROM courses WHERE id='$courseId'";
$result = $conn->query($sql);
$course = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Edit Course</title>
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

            form {
                margin-bottom: 20px;
            }

            label {
                display: block;
                font-size: 16px;
                margin-bottom: 5px;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 3px;
                border: 1px solid #ccc;
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
            <h2>Edit Course</h2>
            <form action="" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                <div class="form-group">
                    <label for="kode">Kode:</label>
                    <input type="text" id="kode" name="kode" value="<?php echo $course['kode']; ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $course['nama']; ?>" required class="form-control">
                </div>
                <input type="submit" value="Update Course" class="btn btn-primary">
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>

</html>

