<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }
if (!isset($_GET['id'])) { header('Location: admin_dashboard.php'); exit(); }
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM departments WHERE id=$id LIMIT 1");
if (mysqli_num_rows($q) == 0) { header('Location: admin_dashboard.php?msg=Department+not+found'); exit(); }
$dept = mysqli_fetch_assoc($q);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $hod_name = mysqli_real_escape_string($conn, $_POST['hod_name']);
    $newImage = $dept['hod_image'];

    if (!empty($_FILES['hod_image']['name'])) {
        $target_dir = 'assets/uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time().'_'.basename($_FILES['hod_image']['name']);
        $target_file = $target_dir.$filename;
        if (move_uploaded_file($_FILES['hod_image']['tmp_name'], $target_file)) {
            if (!empty($dept['hod_image']) && file_exists($target_dir.$dept['hod_image'])) @unlink($target_dir.$dept['hod_image']);
            $newImage = $filename;
        } else {
            $message = 'Image upload failed.';
        }
    }

    if ($message === '') {
        mysqli_query($conn, "UPDATE departments SET name='".mysqli_real_escape_string($conn,$name)."', hod_name='".mysqli_real_escape_string($conn,$hod_name)."', hod_image='".mysqli_real_escape_string($conn,$newImage)."' WHERE id=$id");
        header('Location: admin_dashboard.php?msg=Department+updated');
        exit();
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Department</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light" style="background:#fff;">
  <div class="container">
    <a href="admin_dashboard.php" class="btn btn-outline-primary">&larr; Back</a>
    <span class="navbar-brand">Edit Department</span>
  </div>
</nav>
<div class="container mt-4">
  <?php if($message): ?><div class="alert alert-danger"><?=$message?></div><?php endif; ?>
  <div class="card p-3">
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3"><label>Department Name</label><input name="name" class="form-control" required value="<?php echo htmlspecialchars($dept['name']); ?>"></div>
      <div class="mb-3"><label>HOD Name</label><input name="hod_name" class="form-control" required value="<?php echo htmlspecialchars($dept['hod_name']); ?>"></div>
      <div class="mb-3">
        <label>Current HOD Image</label><br>
        <img src="assets/uploads/<?php echo htmlspecialchars($dept['hod_image']); ?>" style="height:160px;object-fit:cover;border-radius:8px;">
      </div>
      <div class="mb-3"><label>Replace HOD Image (optional)</label><input type="file" name="hod_image" class="form-control" accept="image/*"></div>
      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>
</body>
</html>
