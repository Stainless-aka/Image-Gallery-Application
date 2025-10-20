<?php
session_start();
include('config.php');

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Events
if (isset($_POST['add_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        mysqli_query($conn, "INSERT INTO events (title, image) VALUES ('$title', '$image')");
    }
}

// Departments
if (isset($_POST['add_department'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hod_name = mysqli_real_escape_string($conn, $_POST['hod_name']);
    $hod_image = $_FILES['hod_image']['name'];
    $target = "uploads/" . basename($hod_image);
    if (move_uploaded_file($_FILES['hod_image']['tmp_name'], $target)) {
        mysqli_query($conn, "INSERT INTO departments (name, hod_name, hod_image) VALUES ('$name', '$hod_name', '$hod_image')");
    }
}

// Staffs
if (isset($_POST['add_staff'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
    mysqli_query($conn, "INSERT INTO staffs (name, position, department_id) VALUES ('$name', '$position', '$department_id')");
}

// Fetch data
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY id DESC");
$staffs = mysqli_query($conn, "SELECT s.*, d.name AS dept_name FROM staffs s JOIN departments d ON s.department_id = d.id ORDER BY s.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Federal University Gashua</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #eef2f3, #8e9eab);
    font-family: 'Poppins', sans-serif;
}
.navbar {
    backdrop-filter: blur(12px);
}
.card {
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
.section-title {
    border-left: 5px solid #0d6efd;
    padding-left: 12px;
    font-weight: 600;
}
.table-striped tbody tr:hover {
    background-color: #f1f7ff;
}
.badge-gradient {
    background: linear-gradient(to right, #007bff, #6610f2);
    color: #fff;
}
.fab {
    position: fixed;
    bottom: 25px;
    right: 25px;
    border-radius: 50%;
    font-size: 24px;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">🎓 Admin Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a href="logout.php" class="btn btn-outline-light ms-auto">Logout</a>
  </div>
</nav>

<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar">
  <div class="offcanvas-header bg-primary text-white">
    <h5 class="offcanvas-title">Admin Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <a href="#events" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-calendar-event"></i> Events</a>
    <a href="#departments" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-diagram-3"></i> Departments</a>
    <a href="#staffs" class="btn btn-outline-primary w-100"><i class="bi bi-people"></i> Staffs</a>
  </div>
</div>

<div class="container my-5">

<!-- EVENTS SECTION -->
<section id="events" class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="section-title"><i class="bi bi-image"></i> Uploaded Events</h4>
        <a href="add_event.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Event</a>
    </div>

    <div class="row g-4">
        <?php if (mysqli_num_rows($events) > 0): ?>
            <?php while($event = mysqli_fetch_assoc($events)): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="assets/uploads/<?= htmlspecialchars($event['image']); ?>" class="card-img-top rounded-top" alt="event">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($event['title']); ?></h5>
                        <small class="text-muted d-block mb-2">Posted on <?= date("M d, Y - h:i A", strtotime($event['created_at'])); ?></small>
                        <div class="d-flex justify-content-between">
                            <a href="edit_event.php?id=<?= $event['id']; ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                            <a href="delete_event.php?id=<?= $event['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this event?');"><i class="bi bi-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted text-center">No events uploaded yet.</p>
        <?php endif; ?>
    </div>
</section>

<!-- ================== DEPARTMENTS SECTION ================== -->
<div class="mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 text-primary fw-bold">
      <i class="bi bi-building"></i> Departments Overview
    </h4>
    <a href="add_department.php" class="btn btn-primary btn-sm shadow-sm">
      <i class="bi bi-plus-circle"></i> Add Department
    </a>
  </div>

  <div class="row g-4">
    <?php if (mysqli_num_rows($departments) > 0): ?>
      <?php while($dept = mysqli_fetch_assoc($departments)): ?>
      <div class="col-md-4 col-sm-6">
        <div class="card border-0 shadow-sm h-100 hover-shadow transition">
          <img src="assets/uploads/<?php echo htmlspecialchars($dept['hod_image']); ?>"
               alt="HOD Image"
               class="card-img-top rounded-top"
               style="height:200px; object-fit:cover;">

          <div class="card-body text-center">
            <h5 class="card-title fw-semibold text-dark">
              <?php echo htmlspecialchars($dept['name']); ?>
            </h5>
            <p class="text-muted mb-2">HOD: <?php echo htmlspecialchars($dept['hod_name']); ?></p>

            <div class="d-flex justify-content-center gap-2 mt-2">
              <a href="edit_department.php?id=<?php echo $dept['id']; ?>"
                 class="btn btn-outline-primary btn-sm px-3">
                 <i class="bi bi-pencil-square"></i> Edit
              </a>
              <a href="delete_department.php?id=<?php echo $dept['id']; ?>"
                 class="btn btn-outline-danger btn-sm px-3"
                 onclick="return confirm('Delete this department? All staffs in it will be removed.');">
                 <i class="bi bi-trash"></i> Delete
              </a>
            </div>

            <div class="mt-3">
              <a href="manage_staffs.php?dept_id=<?php echo $dept['id']; ?>"
                 class="btn btn-secondary btn-sm px-4">
                 <i class="bi bi-people"></i> Manage Staffs
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <div class="alert alert-info">No departments added yet.</div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- STAFF SECTION -->
<section id="staffs" class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="section-title"><i class="bi bi-person-gear"></i> Manage Staffs</h4>
            </div>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while($s = mysqli_fetch_assoc($staffs)): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']); ?></td>
                    <td><?= htmlspecialchars($s['position']); ?></td>
                    <td><span class="badge badge-gradient"><?= htmlspecialchars($s['dept_name']); ?></span></td>
                    <td class="text-center">
                        <button class="btn btn-outline-primary btn-sm editBtn"
                            data-id="<?= $s['id']; ?>"
                            data-name="<?= htmlspecialchars($s['name']); ?>"
                            data-position="<?= htmlspecialchars($s['position']); ?>"
                            data-dept="<?= $s['department_id']; ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="?delete_staff=<?= $s['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this staff?');"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Floating Button -->
<a href="#top" class="btn btn-primary fab shadow"><i class="bi bi-arrow-up"></i></a>

</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Edit Staff</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="edit_name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="text" name="edit_position" id="edit_position" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="edit_department_id" id="edit_department_id" class="form-select" required>
              <option value="">Select Department</option>
              <?php
              $deptList = mysqli_query($conn, "SELECT * FROM departments");
              while($d = mysqli_fetch_assoc($deptList)){
                  echo "<option value='{$d['id']}'>{$d['name']}</option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_staff" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const editModal = new bootstrap.Modal(document.getElementById('editStaffModal'));
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_position').value = btn.dataset.position;
        document.getElementById('edit_department_id').value = btn.dataset.dept;
        editModal.show();
    });
});
</script>
</body>
</html>
