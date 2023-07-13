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

$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

    <head>
        <title>List Mata Kuliah</title>
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

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }

            th {
                background-color: #1da1f2;
                color: #fff;
                font-weight: bold;
            }

            a {
                color: #1da1f2;
                text-decoration: none;
                margin-right: 10px;
            }

            a:hover {
                text-decoration: underline;
            }

            .btn {
                padding: 10px 20px;
                background-color: #1da1f2;
                color: #fff;
                border: none;
                border-radius: 3px;
                cursor: pointer;
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
        </style>
        <script>
            function confirmDelete() {
                return confirm("Apakah Anda yakin ingin menghapus mata kuliah ini?");
            }
        </script>
    </head>

    <body>
        <div class="container">
            <h2>List Mata Kuliah</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['kode'] . "</td>
                                    <td>" . $row['nama'] . "</td>
                                    <td>
                                        <a href='edit_course.php?id=" . $row['id'] . "'>Edit</a>
                                        <a href='delete_course.php?id=" . $row['id'] . "' onclick='return confirmDelete();'>Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <a href="add_course.php" class="btn btn-primary">Masukkan Mata Kuliah</a>
                </div>
                <div class="col-md-6">
                    <a href="index.php" class="btn btn-link">Kembali</a>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    </body>

</html>


