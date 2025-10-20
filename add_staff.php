<?php
session_start();
include('config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$dept_id = isset($_GET['dept_id']) ? intval($_GET['dept_id']) : 0;
$departments = mysqli_query($conn, "SELECT * FROM departments ORDER BY name ASC");
$msg = '';

// ADD STAFF
if (isset($_POST['add_staff'])) {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $department_id = intval($_POST['department_id']);

    if (!empty($name) && !empty($position) && $department_id > 0) {
        $insert = mysqli_prepare($conn, "INSERT INTO staffs (name, position, department_id, created_at) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($insert, "ssi", $name, $position, $department_id);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);

        // Redirect using GET to prevent duplicate insertion (Post/Redirect/Get)
        header("Location: manage_staffs.php?dept_id=$department_id&msg=Staff+added+successfully");
        exit();
    } else {
        $msg = '⚠️ Please fill all fields.';
    }
}

// DELETE STAFF
if (isset($_GET['delete_staff'])) {
    $id = intval($_GET['delete_staff']);
    mysqli_query($conn, "DELETE FROM staffs WHERE id=$id");
    header("Location: manage_staffs.php?dept_id=$dept_id&msg=Staff+deleted");
    exit();
}

// UPDATE STAFF
if (isset($_POST['update_staff'])) {
    $id = intval($_POST['staff_id']);
    $name = trim($_POST['edit_name']);
    $position = trim($_POST['edit_position']);
    $department_id = intval($_POST['edit_department_id']);

    if (!empty($name) && !empty($position) && $department_id > 0) {
        $update = mysqli_prepare($conn, "UPDATE staffs SET name=?, position=?, department_id=? WHERE id=?");
        mysqli_stmt_bind_param($update, "ssii", $name, $position, $department_id, $id);
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);

        header("Location: manage_staffs.php?dept_id=$department_id&msg=Staff+updated");
        exit();
    }
}

$staffs = mysqli_query($conn, "SELECT s.*, d.name AS dept_name FROM staffs s JOIN departments d ON s.department_id = d.id ORDER BY s.created_at DESC");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Staffs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['msg'])) echo '<div class="alert alert-success">'.htmlspecialchars($_GET['msg']).'</div>'; ?>
    <?php if ($msg) echo '<div class="alert alert-danger">'.$msg.'</div>'; ?>

    <div class="card p-3">
        <h5>Manage Staffs</h5>

        <!-- ADD STAFF -->
        <form method="POST" class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Staff Name" required></div>
            <div class="col-md-3"><input type="text" name="position" class="form-control" placeholder="Position" required></div>
            <div class="col-md-3">
                <select name="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    <?php
                    $deptQuery = mysqli_query($conn, "SELECT * FROM departments");
                    while($d = mysqli_fetch_assoc($deptQuery)) {
                        echo "<option value='{$d['id']}'>{$d['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3"><button type="submit" name="add_staff" class="btn btn-primary w-100">Add Staff</button></div>
        </form>

        <!-- STAFF TABLE -->
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Position</th><th>Department</th><th>Actions</th></tr></thead>
            <tbody>
            <?php while ($s = mysqli_fetch_assoc($staffs)): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= htmlspecialchars($s['position']) ?></td>
                    <td><?= htmlspecialchars($s['dept_name']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal" 
                            data-id="<?= $s['id'] ?>" 
                            data-name="<?= htmlspecialchars($s['name']) ?>" 
                            data-position="<?= htmlspecialchars($s['position']) ?>" 
                            data-dept="<?= $s['department_id'] ?>">Edit</button>
                        <a href="?delete_staff=<?= $s['id'] ?>&dept_id=<?= $dept_id ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this staff?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- EDIT STAFF MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Staff</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="staff_id" id="edit_staff_id">
        <div class="mb-3"><label>Name</label><input type="text" name="edit_name" id="edit_name" class="form-control" required></div>
        <div class="mb-3"><label>Position</label><input type="text" name="edit_position" id="edit_position" class="form-control" required></div>
        <div class="mb-3"><label>Department</label>
            <select name="edit_department_id" id="edit_department_id" class="form-select" required>
                <?php
                $deptOptions = mysqli_query($conn, "SELECT * FROM departments");
                while($d = mysqli_fetch_assoc($deptOptions)) {
                    echo "<option value='{$d['id']}'>{$d['name']}</option>";
                }
                ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update_staff" class="btn btn-success">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
var editModal = document.getElementById('editModal');
editModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  document.getElementById('edit_staff_id').value = button.getAttribute('data-id');
  document.getElementById('edit_name').value = button.getAttribute('data-name');
  document.getElementById('edit_position').value = button.getAttribute('data-position');
  document.getElementById('edit_department_id').value = button.getAttribute('data-dept');
});
</script>

</body>
</html>
