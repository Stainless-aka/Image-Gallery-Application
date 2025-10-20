<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }
if (!isset($_GET['id'])) { header('Location: manage_staffs.php'); exit(); }
$id = intval($_GET['id']);
if (mysqli_query($conn, "DELETE FROM staffs WHERE id=$id")) {
    header('Location: manage_staffs.php?msg=Staff+deleted');
    exit();
} else {
    header('Location: manage_staffs.php?msg=Delete+failed');
    exit();
}
?>
