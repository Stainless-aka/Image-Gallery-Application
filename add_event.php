<?php
session_start();
include('config.php');

// Only admins should access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

if (isset($_POST['upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $image = $_FILES['image']['name'];
    $target_dir = "assets/uploads/";
    $target_file = $target_dir . basename($image);

    // Check if directory exists, if not, create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Allow only image types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_types)) {
        $message = "<div class='alert alert-danger text-center'>Invalid file type. Only JPG, PNG, or GIF allowed.</div>";
    } elseif (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert into database
        $sql = "INSERT INTO events (title, image, created_at) VALUES ('$title', '$image', NOW())";
        if (mysqli_query($conn, $sql)) {
            $message = "<div class='alert alert-success text-center'>Event uploaded successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Database error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger text-center'>Failed to upload image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Event | Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body {
    background: #f0f4ff;
    min-height: 100vh;
}
.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.navbar {
    background-color: #0d6efd;
}
.navbar a {
    color: white !important;
    font-weight: bold;
    text-decoration: none;
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid px-4">
    <a href="admin_dashboard.php" class="navbar-brand">&larr; Back</a>
    <span class="navbar-text mx-auto">Add New Event</span>
  </div>
</nav>

<div class="container mt-5 pt-4">
    <div class="card p-4 mx-auto" style="max-width:500px;">
        <h4 class="text-center text-primary mb-3">Upload Event</h4>
        <?php echo $message; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Event Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter event title" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Event Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" name="upload" class="btn btn-primary w-100">
                <i class="fa fa-upload"></i> Upload Event
            </button>
        </form>
    </div>
</div>
</body>
</html>
