<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }

$dept_id = isset($_GET['dept_id']) ? intval($_GET['dept_id']) : 0;
$where = $dept_id ? " WHERE department_id=$dept_id " : "";

$staffs = mysqli_query($conn, "SELECT s.*, d.name as dept_name FROM staffs s LEFT JOIN departments d ON s.department_id=d.id $where ORDER BY s.id DESC");
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY name ASC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Staffs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<nav class="navbar navbar-light" style="background:#fff;">
  <div class="container">
    <a href="admin_dashboard.php" class="btn btn-outline-primary">&larr; Back</a>
    <span class="navbar-brand">Manage Staffs</span>
  </div>
</nav>

<div class="container mt-4">
  <div class="mb-3">
    <a href="add_staff.php<?php echo $dept_id ? '?dept_id='.$dept_id : ''; ?>" class="btn btn-primary">Add Staff</a>
  </div>

  <div class="card p-3">
    <table class="table table-striped">
      <thead><tr><th>ID</th><th>Name</th><th>Position</th><th>Department</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(mysqli_num_rows($staffs)>0): while($s=mysqli_fetch_assoc($staffs)): ?>
        <tr>
          <td><?php echo $s['id']; ?></td>
          <td><?php echo htmlspecialchars($s['name']); ?></td>
          <td><?php echo htmlspecialchars($s['position']); ?></td>
          <td><?php echo htmlspecialchars($s['dept_name']); ?></td>
          <td>
            <a href="edit_staff.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="delete_staff.php?id=<?php echo $s['id']; ?>" onclick="return confirm('Delete this staff?');" class="btn btn-sm btn-outline-danger">Delete</a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="5" class="text-muted">No staffs found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
