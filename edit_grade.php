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
    $courseId = $_POST['course_id']; // Menggunakan field baru course_id
    $nilai = $_POST['nilai'];
    $grade = getGrade($nilai);

    $sql = "UPDATE grades SET course_id='$courseId', nilai='$nilai', grade='$grade' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT * FROM grades WHERE id='$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql = "SELECT * FROM courses"; // Menambahkan query untuk mengambil data mata kuliah
$courses = $conn->query($sql);

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

$conn->close();
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Edit Grade</title>
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

            select,
            input[type="number"] {
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
            <h2>Edit Grade</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select name="course_id" id="course" required class="form-control">
                        <?php
                        if ($courses->num_rows > 0) {
                            while ($course = $courses->fetch_assoc()) {
                                $selected = ($row['course_id'] == $course['id']) ? 'selected' : '';
                                echo "<option value='" . $course['id'] . "' $selected>" . $course['nama'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nilai">Nilai:</label>
                    <input type="number" name="nilai" id="nilai" value="<?php echo $row['nilai']; ?>" required class="form-control">
                </div>
                <input type="submit" value="Update" class="btn btn-primary">
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>

</html>

