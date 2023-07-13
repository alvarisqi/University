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

$studentId = $_GET['student_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Code to add grades for the student
    $mataKuliahId = $_POST['mata_kuliah'];
    $nilai = $_POST['nilai'];
    $grade = getGrade($nilai);

    // Retrieve course data from list_courses.php based on the selected mata_kuliah
    $sqlCourse = "SELECT * FROM courses WHERE id='$mataKuliahId'";
    $courseResult = $conn->query($sqlCourse);
    $course = $courseResult->fetch_assoc();
    $mataKuliah = $course['nama'];

    // Insert the new grade into the database
    $sql = "INSERT INTO grades (student_id, course, nilai, grade) VALUES ('$studentId', '$mataKuliah', '$nilai', '$grade')";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to view_grades.php after successfully adding the grade
        header("Location: view_grades.php?student_id=$studentId");
        exit();
    } else {
        echo "Error adding grade: " . $conn->error;
    }
}


// Retrieve student information
$sql = "SELECT * FROM students WHERE id='$studentId'";
$studentResult = $conn->query($sql);
$student = $studentResult->fetch_assoc();

// Retrieve grades for the student
$sql = "SELECT * FROM grades WHERE student_id='$studentId'";
$gradesResult = $conn->query($sql);

// Retrieve courses from list_courses.php
$sqlCourses = "SELECT * FROM courses";
$coursesResult = $conn->query($sqlCourses);
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Hasil Studi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f1f1f1;
                padding: 20px;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 20px;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                margin-bottom: 20px;
            }

            th, td {
                padding: 8px;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f1f1f1;
            }

            form {
                margin-bottom: 20px;
            }

            label {
                font-weight: bold;
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

            a {
                color: #1da1f2;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
        <script>
            function confirmDelete() {
                return confirm("Apakah Anda yakin ingin menghapus nilai ini?");
            }
        </script>
    </head>

    <body>
        <div class="container">
            <h2>Hasil Studi <?php echo $student['name']; ?></h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Nilai</th>
                        <th>Grade</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($gradesResult->num_rows > 0) {
                        while ($grade = $gradesResult->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $grade['course'] . "</td>
                                <td>" . $grade['nilai'] . "</td>
                                <td>" . $grade['grade'] . "</td>
                                <td>
                                    <a href='edit_grade.php?id=" . $grade['id'] . "' class='mr-4'>Edit</a>
                                    <a href='delete_grade.php?id=" . $grade['id'] . "' onclick='return confirmDelete();'>Hapus</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No grades found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Add form to add new grade -->
            <h3>Masukkan Nilai Mata Kuliah</h3>
            <form action="" method="POST">
                <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
                <div class="form-group">
                    <label>Mata Kuliah:</label>
                    <select name="mata_kuliah" class="form-control" required>
                        <?php
                        if ($coursesResult->num_rows > 0) {
                            while ($course = $coursesResult->fetch_assoc()) {
                                echo "<option value='" . $course['id'] . "'>" . $course['nama'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No courses available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nilai:</label>
                    <input type="number" name="nilai" class="form-control" required>
                </div>
                <input type="submit" value="Add Grade" class="btn btn-primary">
            </form>

            <a href="index.php" class="btn btn-link">Kembali</a>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>


</html>

