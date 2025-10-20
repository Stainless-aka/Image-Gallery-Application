<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }
if (!isset($_GET['id'])) { header('Location: admin_dashboard.php'); exit(); }
$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM events WHERE id=$id LIMIT 1");
if (mysqli_num_rows($res) == 0) { header('Location: admin_dashboard.php?msg=Event+not+found'); exit(); }
$row = mysqli_fetch_assoc($res);
$img = $row['image'];
if (mysqli_query($conn, "DELETE FROM events WHERE id=$id")) {
    $path = 'assets/uploads/'.$img;
    if (!empty($img) && file_exists($path)) @unlink($path);
    header('Location: admin_dashboard.php?msg=Event+deleted');
    exit();
} else {
    header('Location: admin_dashboard.php?msg=Delete+failed');
    exit();
}
?>
