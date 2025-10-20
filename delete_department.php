<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }
if (!isset($_GET['id'])) { header('Location: admin_dashboard.php'); exit(); }
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM departments WHERE id=$id LIMIT 1");
if (mysqli_num_rows($res) == 0) { header('Location: admin_dashboard.php?msg=Department+not+found'); exit(); }
$row = mysqli_fetch_assoc($res);
$img = $row['hod_image'];

// deleting department will cascade delete staffs (if FK with ON DELETE CASCADE), but we'll still remove the image
if (mysqli_query($conn, "DELETE FROM departments WHERE id=$id")) {
    $path = 'assets/uploads/'.$img;
    if (!empty($img) && file_exists($path)) @unlink($path);
    header('Location: admin_dashboard.php?msg=Department+deleted');
    exit();
} else {
    header('Location: admin_dashboard.php?msg=Delete+failed');
    exit();
}
?>
