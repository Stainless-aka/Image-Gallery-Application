<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role'])) { header('Location: login.php'); exit(); }
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY name ASC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Departments | FUG</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  background: #f4f8fc;
  font-family: 'Segoe UI', sans-serif;
}
.navbar {
  background: #003366;
}
.card {
  border: none;
  border-radius: 16px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.hod-img {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #003366;
  margin-right: 15px;
}
.department-header {
  border-bottom: 2px solid #00336620;
  padding-bottom: 10px;
  margin-bottom: 15px;
}
.staff-card {
  background: #f8fbff;
  border-radius: 10px;
  padding: 10px 12px;
  transition: background 0.2s ease;
}
.staff-card:hover {
  background: #e9f2ff;
}
.text-primary {
  color: #003366 !important;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white"><i class="bi bi-building me-2"></i>Federal University Gashua</a>
    <div class="d-flex">
      <a href="javascript:history.back()" class="btn btn-light me-2"><i class="bi bi-arrow-left"></i> Back</a>
      <a href="logout.php" class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container mt-5">
  <div class="d-flex align-items-center mb-4">
    <i class="bi bi-diagram-3-fill text-primary fs-2 me-2"></i>
    <h3 class="fw-bold text-primary mb-0">Departments</h3>
  </div>

  <?php if(mysqli_num_rows($departments)>0): ?>
    <div class="row g-4">
      <?php while($d=mysqli_fetch_assoc($departments)): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card p-3 h-100">
            <div class="department-header d-flex align-items-center">
              <img src="assets/uploads/<?php echo htmlspecialchars($d['hod_image']); ?>" alt="HOD" class="hod-img">
              <div>
                <h5 class="text-primary mb-0"><?php echo htmlspecialchars($d['name']); ?></h5>
                <small class="text-muted">HOD: <strong><?php echo htmlspecialchars($d['hod_name']); ?></strong></small>
              </div>
            </div>

            <div>
              <h6 class="fw-bold text-secondary mb-2"><i class="bi bi-people-fill me-1"></i> Staff Members</h6>
              <div class="row">
                <?php
                  $staffs = mysqli_query($conn, "SELECT * FROM staffs WHERE department_id='".intval($d['id'])."' ORDER BY id ASC");
                  if (mysqli_num_rows($staffs) > 0):
                    while($s = mysqli_fetch_assoc($staffs)):
                ?>
                  <div class="col-12 mb-2">
                    <div class="staff-card d-flex align-items-center">
                      <i class="bi bi-person-circle text-primary fs-4 me-2"></i>
                      <div>
                        <div class="fw-semibold"><?php echo htmlspecialchars($s['name']); ?></div>
                        <div class="text-muted small"><?php echo htmlspecialchars($s['position']); ?></div>
                      </div>
                    </div>
                  </div>
                <?php endwhile; else: ?>
                  <p class="text-muted small ps-3">No staff members listed.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center shadow-sm">No departments found.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
