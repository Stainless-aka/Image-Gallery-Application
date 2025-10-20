<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image']['name'])) {
        $target_dir = 'assets/uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time().'_'.basename($_FILES['image']['name']);
        $target_file = $target_dir.$filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            mysqli_query($conn, "INSERT INTO events (title,image,created_at) VALUES ('".$title."','".$filename."',NOW())");
            header('Location: admin_dashboard.php?msg=Event+uploaded');
            exit();
        } else {
            echo 'Upload failed.';
        }
    }
}
?>