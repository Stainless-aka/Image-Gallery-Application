<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }
if (!isset($_GET['id'])) { header('Location: manage_staffs.php'); exit(); }
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM staffs WHERE id=$id LIMIT 1");
if (!mysqli_num_rows($q)) { header('Location: manage_staffs.php?msg=Staff+not+found'); exit(); }
$staff = mysqli_fetch_assoc($q);
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY name ASC");
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department_id = intval($_POST['department_id']);
    mysqli_query($conn, "UPDATE staffs SET name='".mysqli_real_escape_string($conn,$name)."', position='".mysqli_real_escape_string($conn,$position)."', department_id=".$department_id." WHERE id=$id");
    header('Location: manage_staffs.php?dept_id='.$department_id.'&msg=Staff+updated');
    exit();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Staff</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<nav class="navbar navbar-light" style="background:#fff;">
  <div class="container">
    <a href="manage_staffs.php" class="btn btn-outline-primary">&larr; Back</a>
    <span class="navbar-brand">Edit Staff</span>
  </div>
</nav>
<div class="container mt-4">
  <div class="card p-3">
    <form method="post">
      <div class="mb-3"><label>Name</label><input name="name" class="form-control" required value="<?php echo htmlspecialchars($staff['name']); ?>"></div>
      <div class="mb-3"><label>Position</label><input name="position" class="form-control" required value="<?php echo htmlspecialchars($staff['position']); ?>"></div>
      <div class="mb-3"><label>Department</label>
        <select name="department_id" class="form-select" required>
          <?php while($d=mysqli_fetch_assoc($departments)): ?>
            <option value="<?php echo $d['id']; ?>" <?php echo ($staff['department_id']==$d['id'])?'selected':''; ?>><?php echo htmlspecialchars($d['name']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>
</body>
</html>
