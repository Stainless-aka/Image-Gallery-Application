<?php
session_start();
include 'config.php';
if (!isset($_SESSION['role'])) { 
  header('Location: login.php'); 
  exit(); 
}
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Events | FUG</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  background-color: #f4f9ff;
  font-family: "Poppins", sans-serif;
}
.navbar {
  background: #003366;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.card {
  border: none;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}
.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 22px rgba(0,0,0,0.12);
}
.card-img-top {
  height: 220px;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.card:hover .card-img-top {
  transform: scale(1.05);
}
.fade-in {
  animation: fadeIn 0.8s ease-in-out;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
.event-date {
  background: #e3f2fd;
  color: #003366;
  font-size: 0.9rem;
  border-radius: 30px;
  padding: 4px 10px;
  display: inline-block;
  margin-top: 8px;
}
.search-bar {
  max-width: 400px;
  margin: 0 auto 30px auto;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white"><i class="bi bi-calendar-event me-2"></i>Federal University Gashua</a>
    <div class="d-flex">
      <a href="javascript:history.back()" class="btn btn-light me-2"><i class="bi bi-arrow-left"></i> Back</a>
      <a href="logout.php" class="btn btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4 fade-in">
  <div class="text-center mb-4">
    <h3 class="fw-bold text-primary"><i class="bi bi-stars me-2"></i>Latest Events</h3>
    <p class="text-muted">Stay updated with the latest events happenings in <b>FUGA</b></p>
  </div>

  <!-- 🔍 Search Bar -->
  <div class="search-bar input-group mb-4">
    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
    <input type="text" id="searchInput" class="form-control" placeholder="Search event by title or image name...">
  </div>

  <div class="row g-4" id="eventsContainer">
    <?php if(mysqli_num_rows($events)>0): 
      while($ev=mysqli_fetch_assoc($events)): 
        $isNew = (time() - strtotime($ev['created_at'])) < (7 * 24 * 60 * 60); // Show "New" if within 7 days
    ?>
      <div class="col-md-4 col-sm-6 event-card">
        <div class="card h-100">
          <img src="assets/uploads/<?php echo htmlspecialchars($ev['image']); ?>" alt="Event Image" class="card-img-top">
          <div class="card-body text-center">
            <h5 class="text-primary fw-semibold mb-1 event-title">
              <?php echo htmlspecialchars($ev['title']); ?>
            </h5>
            <?php if($isNew): ?>
              <span class="badge bg-success mb-2">New</span>
            <?php endif; ?>
            <div class="event-date">
              <i class="bi bi-clock me-1"></i>
              <?php echo date('M d, Y - h:i A', strtotime($ev['created_at'])); ?>
            </div>
            <div class="d-none event-image-name"><?php echo htmlspecialchars($ev['image']); ?></div>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="col-12 text-center">
        <p class="text-muted fs-5"><i class="bi bi-exclamation-circle"></i> No events found.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 🔍 Live search filter
document.getElementById('searchInput').addEventListener('input', function() {
  const query = this.value.toLowerCase();
  const cards = document.querySelectorAll('.event-card');

  cards.forEach(card => {
    const title = card.querySelector('.event-title').textContent.toLowerCase();
    const image = card.querySelector('.event-image-name').textContent.toLowerCase();
    card.style.display = (title.includes(query) || image.includes(query)) ? '' : 'none';
  });
});
</script>
</body>
</html>
