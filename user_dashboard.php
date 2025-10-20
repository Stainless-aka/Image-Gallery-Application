<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

$events = mysqli_query($conn, "SELECT * FROM events ORDER BY created_at DESC");
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY id ASC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>User Dashboard | FUG</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: #f4f9ff;
  font-family: Arial, sans-serif;
}
.navbar {
  background: #003366;
}
.card {
  border-radius: 14px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  border: none;
  transition: all 0.3s ease-in-out;
}
.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.btn-primary {
  background: #003366;
  border: none;
}
.btn-outline-primary {
  border-color: #003366;
  color: #003366;
}
.btn-outline-primary:hover {
  background: #003366;
  color: #fff;
}
.section-title {
  font-weight: bold;
  font-size: 1.25rem;
  color: #003366;
}
.event-icon, .dept-icon {
  font-size: 3rem;
  color: #003366;
  margin-bottom: 10px;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white">User Dashboard</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">

  <div class="row justify-content-center g-4">
    <!-- Events Block -->
    <div class="col-md-6">
      <div class="card p-4 text-center">
        <div class="event-icon">🖼️</div>
        <h5 class="section-title">Events</h5>
        <p class="text-muted">Stay updated with the latest campus events and programs.</p>
        <a href="events.php" class="btn btn-outline-primary mt-2 px-4">View Events</a>
      </div>
    </div>

    <!-- Departments Block -->
    <div class="col-md-6">
      <div class="card p-4 text-center">
        <div class="dept-icon">🏢</div>
        <h5 class="section-title">Departments</h5>
        <p class="text-muted">Explore various departments and connect with academic communities.</p>
        <a href="departments.php" class="btn btn-outline-primary mt-2 px-4">View Departments</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
