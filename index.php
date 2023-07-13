<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['admin'])) {
    // Redirect to the login page
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "university";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get admin data
$adminName = $_SESSION['admin'];
$sql = "SELECT * FROM admins WHERE username='$adminName'";
$adminResult = $conn->query($sql);
$admin = $adminResult->fetch_assoc();
$photo = $admin['photo'];

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['photo'])) {
        $file = $_FILES['photo'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        // Check if file is uploaded successfully
        if ($fileError === UPLOAD_ERR_OK) {
            // Get file extension
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            // Define allowed file extensions
            $allowedExtensions = array('jpg', 'jpeg', 'png');

            // Check if file extension is allowed
            if (in_array($fileExt, $allowedExtensions)) {
                // Define target directory to save the uploaded file
                $uploadDir = 'uploads/';
                // Generate a unique name for the uploaded file
                $newFileName = uniqid('', true) . '.' . $fileExt;
                // Define the target path for the uploaded file
                $targetPath = $uploadDir . $newFileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    // Update the admin's photo in the database
                    $adminId = $admin['id'];
                    $sql = "UPDATE admins SET photo='$targetPath' WHERE id='$adminId'";
                    $conn->query($sql);

                    // Refresh the page to display the new photo
                    header('Location: index.php');
                    exit();
                } else {
                    echo 'Failed to upload the file.';
                }
            } else {
                echo 'Invalid file extension. Only JPG, JPEG, and PNG files are allowed.';
            }
        } else {
            echo 'Error occurred while uploading the file.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>University Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-top: 0;
        }

        h2 {
            margin-top: 0;
        }

        h3 {
            margin-top: 0;
        }

        .logout-link {
            float: right;
            margin-top: -35px;
            color: #385898;
            text-decoration: none;
            font-size: 14px;
        }

        .add-student-link,
        .add-grade-link {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .add-student-link:hover,
        .add-grade-link:hover {
            background-color: #45a049;
        }

        .manage-table {
            margin-top: 20px;
        }

        .manage-table th,
        .manage-table td {
            padding: 10px;
        }

        .manage-table th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            text-align: left;
            font-size: 14px;
        }

        .manage-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .manage-table a {
            color: #007bff;
            margin-right: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .manage-table a:hover {
            text-decoration: underline;
        }

        .manage-table .action-buttons {
            white-space: nowrap;
        }

        .course-table {
            margin-top: 20px;
        }

        .course-table th,
        .course-table td {
            padding: 10px;
        }

        .course-table th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            text-align: left;
            font-size: 14px;
        }

        .course-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .course-table a {
            color: #007bff;
            margin-right: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .course-table a:hover {
            text-decoration: underline;
        }

        .grade-table {
            margin-top: 20px;
        }

        .grade-table th,
        .grade-table td {
            padding: 10px;
        }

        .grade-table th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            text-align: left;
            font-size: 14px;
        }

        .grade-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .grade-table .grade-column {
            width: 100px;
        }

        .grade-table .action-buttons {
            white-space: nowrap;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .admin-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .admin-profile span {
            font-size: 16px;
            font-weight: bold;
        }

        .upload-photo-form {
            display: inline-block;
            margin-left: 10px;
        }

        .upload-photo-form input[type="file"] {
            display: none;
        }

        .upload-photo-form label {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 9px;
            line-height: 1;
            height: 20px;
        }

        .upload-photo-form label:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this student?");
        }

        function uploadPhoto() {
            document.getElementById('photo-file').click();
        }
    </script>
</head>

<body>
    <div class="container">
        <h1 style="font-size: 36px;">University Management System</h1>

        <?php if (isset($_SESSION['admin'])) : ?>
            <div class="admin-profile">
                <div style="display: flex; align-items: center;">
                    <img src="<?php echo $photo ? $photo : 'default-profile.jpg'; ?>" alt="Admin Profile">
                    <span style="margin-left: 10px; font-size: 16px; font-weight: bold;">Welcome, <?php echo $_SESSION['admin']; ?></span>
                </div>
                <div class="upload-photo-form" style="margin-top: 10px;">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="photo" id="photo-file" onchange="this.form.submit()" style="display: none;">
                        <label for="photo-file" style="padding: 5px 10px; background-color: #007bff; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 9px; line-height: 1; height: 20px;">Upload Photo</label>
                    </form>
                </div>
            </div>
            <a class="logout-link" href="logout.php">Logout</a>

            <h2 style="font-size: 24px;">Manage Students</h2>
            <a class="add-student-link" href="add_student.php">Add New Student</a>
            <div class="table-responsive">
                <table class="table table-striped manage-table">
                    <thead>
                        <tr>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Program Studi</th>
                            <th scope="col">No HP</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM students";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $row['nim'] . "</td>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $row['program_studi'] . "</td>
                                    <td>" . $row['no_hp'] . "</td>
                                    <td class='action-buttons'>
                                        <a href='edit_student.php?id=" . $row['id'] . "'>Edit</a>
                                        <a href='delete_student.php?id=" . $row['id'] . "' onclick='return confirmDelete();'>Hapus</a>
                                        <a href='view_grades.php?student_id=" . $row['id'] . "'>Lihat Nilai</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php elseif (isset($_SESSION['student'])) : ?>
            <h3 style="font-size: 20px;">Welcome, <?php echo $_SESSION['student']; ?></h3>
            <a class="logout-link" href="logout.php">Logout</a>

            <h2 style="font-size: 24px;">Your Grades</h2>
            <div class="table-responsive">
                <table class="table table-striped grade-table">
                    <thead>
                        <tr>
                            <th scope="col">Course</th>
                            <th scope="col">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $studentId = $_SESSION['student_id'];
                        $sql = "SELECT * FROM grades WHERE student_id = $studentId";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $row['course'] . "</td>
                                    <td>" . $row['grade'] . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No grades found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="login-form">
                <h2 style="font-size: 24px;">Login</h2>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required class="form-control form-control-lg" style="font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required class="form-control form-control-lg" style="font-size: 14px;">
                    </div>
                    <input type="submit" value="Login" class="btn btn-primary btn-lg" style="font-size: 14px;">
                </form>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin']) || isset($_SESSION['student'])) : ?>
            <a class="back-link" href="list_courses.php">Mata Kuliah</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>
