<?php
session_start();
include('config.php');

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

if (isset($_POST['add_department'])) {
    $dept_name = mysqli_real_escape_string($conn, $_POST['dept_name']);
    $hod_name = mysqli_real_escape_string($conn, $_POST['hod_name']);

    // Handle file upload
    if (!empty($_FILES['hod_image']['name'])) {
        $target_dir = "assets/uploads/";
        $file_name = time() . "_" . basename($_FILES["hod_image"]["name"]);
        $target_file = $target_dir . $file_name;

        // Move uploaded image
        if (move_uploaded_file($_FILES["hod_image"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO departments (name, hod_name, hod_image) VALUES ('$dept_name', '$hod_name', '$file_name')";
            if (mysqli_query($conn, $query)) {
                $message = "<div class='alert alert-success text-center'>Department added successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger text-center'>Database error: " . mysqli_error($conn) . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger text-center'>Failed to upload image!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning text-center'>Please select an HOD image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Department | Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(to right, #0d6efd, #ffffff);
    min-height: 100vh;
}
.card {
    max-width: 600px;
    margin: 60px auto;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a href="admin_dashboard.php" class="navbar-brand">&larr; Back</a>
    <span class="navbar-text mx-auto">Add Department</span>
  </div>
</nav>

<div class="card p-4 mt-4 bg-white">
    <h4 class="text-center text-primary mb-3">Add New Department</h4>
    <?php echo $message; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Department Name</label>
            <input type="text" name="dept_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">HOD Name</label>
            <input type="text" name="hod_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload HOD Image</label>
            <input type="file" name="hod_image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" name="add_department" class="btn btn-primary w-100">Add Department</button>
    </form>
</div>

</body>
</html>
