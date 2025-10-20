<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hod_name = mysqli_real_escape_string($conn, $_POST['hod_name']);
    if (!empty($_FILES['hod_image']['name'])) {
        $target_dir = 'assets/uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time().'_'.basename($_FILES['hod_image']['name']);
        $target_file = $target_dir.$filename;
        if (move_uploaded_file($_FILES['hod_image']['tmp_name'], $target_file)) {
            mysqli_query($conn, "INSERT INTO departments (name,hod_name,hod_image,created_at) VALUES ('".$name."','".$hod_name."','".$filename."',NOW())");
            header('Location: admin_dashboard.php?msg=Department+added');
            exit();
        } else {
            echo 'Upload failed.';
        }
    }
}
?>