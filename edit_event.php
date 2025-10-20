<?php
session_start();
include('config.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit(); }

if (!isset($_GET['id'])) { header('Location: admin_dashboard.php'); exit(); }
$id = intval($_GET['id']);
$eventQ = mysqli_query($conn, "SELECT * FROM events WHERE id=$id LIMIT 1");
if (!mysqli_num_rows($eventQ)) { header('Location: admin_dashboard.php?msg=Event+not+found'); exit(); }
$event = mysqli_fetch_assoc($eventQ);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $newImage = $event['image'];

    if (!empty($_FILES['image']['name'])) {
        $target_dir = 'assets/uploads/';
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time().'_'.basename($_FILES['image']['name']);
        $target_file = $target_dir.$filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // delete old file
            if (!empty($event['image']) && file_exists($target_dir.$event['image'])) {
                @unlink($target_dir.$event['image']);
            }
            $newImage = $filename;
        } else {
            $message = 'Image upload failed.';
        }
    }

    if ($message === '') {
        $t = mysqli_real_escape_string($conn, $title);
        mysqli_query($conn, "UPDATE events SET title='$t', image='".mysqli_real_escape_string($conn,$newImage)."' WHERE id=$id");
        header('Location: admin_dashboard.php?msg=Event+updated');
        exit();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Edit Event</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>.container{max-width:700px;margin-top:40px}</style>
</head>
<body>
<nav class="navbar navbar-light" style="background:#fff;box-shadow:0 2px 6px rgba(0,0,0,0.06);">
  <div class="container">
    <a href="admin_dashboard.php" class="btn btn-outline-primary">&larr; Back</a>
    <span class="navbar-brand">Edit Event</span>
  </div>
</nav>

<div class="container">
  <?php if($message): ?><div class="alert alert-danger"><?=$message?></div><?php endif; ?>
  <div class="card p-3">
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" required value="<?php echo htmlspecialchars($event['title']); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Current Image</label><br>
        <img src="assets/uploads/<?php echo htmlspecialchars($event['image']); ?>" style="max-width:100%;height:180px;object-fit:cover;border-radius:8px;">
      </div>
      <div class="mb-3">
        <label class="form-label">Replace Image (optional)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
      </div>
      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>
</body>
</html>
