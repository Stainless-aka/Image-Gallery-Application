<?php
require 'config.php';
$bg = 'assets/bg-hero.jpg';
$bg_ts = file_exists($bg) ? filemtime($bg) : time();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Federal University Gashua - Image Gallery System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(rgba(0, 0, 50, 0.4), rgba(0, 0, 80, 0.5)), 
                  url('<?= htmlspecialchars($bg) ?>?v=<?= $bg_ts ?>') center/cover no-repeat;
      height: 100vh;
      overflow: hidden;
    }
    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      color: #fff;
      animation: fadeInUp 1s ease forwards;
    }
    .glass h1 {
      font-weight: 700;
      color: #fff;
    }
    .glass p {
      color: #dbe6ff;
      letter-spacing: 0.5px;
    }
    .btn-primary {
      background: #0046ad;
      border: none;
      border-radius: 50px;
      padding: 12px 28px;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background: #003366;
      transform: scale(1.05);
    }
    .btn-outline-primary {
      border: 2px solid #fff;
      color: #fff;
      border-radius: 50px;
      padding: 12px 28px;
      transition: all 0.3s ease;
    }
    .btn-outline-primary:hover {
      background: #fff;
      color: #003366;
      transform: scale(1.05);
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    footer {
      position: absolute;
      bottom: 15px;
      width: 100%;
      text-align: center;
      color: #f8f9fa;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <main class="d-flex align-items-center justify-content-center vh-100">
    <div class="card glass text-center p-5">
      <div class="mb-3">
        <i class="bi bi-image text-light" style="font-size: 3rem;"></i>
      </div>
      <h1 class="mb-3">Federal University Gashua</h1>
      <p class="lead mb-4">Image Gallery System</p>
      <div class="d-flex justify-content-center gap-3">
        <a href="login.php" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
        <a href="register.php" class="btn btn-outline-primary btn-lg"><i class="bi bi-person-plus me-2"></i>Register</a>
      </div>
    </div>
  </main>

  <footer>
    <small>&copy; <?= date('Y'); ?> Federal University Gashua | All Rights Reserved</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
