<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // plain-text password check (per project requirement)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
                exit;
            } else {
                header('Location: user_dashboard.php');
                exit;
            }
        } else {
            $error = 'Incorrect password.';
        }
    } else {
        $error = 'Account with that email not found.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login | Federal University Gashua</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, rgba(0, 51, 102, 0.9), rgba(0, 100, 255, 0.8)),
                  url('assets/bg-hero.jpg') center/cover no-repeat;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 40px;
      color: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 420px;
      animation: fadeInUp 1s ease forwards;
    }
    .login-card h4 {
      font-weight: 700;
      color: #fff;
    }
    .form-control {
      border-radius: 50px;
      border: none;
      padding: 12px 18px;
    }
    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.3);
    }
    .btn-primary {
      background: #0046ad;
      border: none;
      border-radius: 50px;
      padding: 12px;
      font-weight: 600;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background: #003366;
      transform: scale(1.05);
    }
    .alert {
      border-radius: 10px;
      background: rgba(255, 0, 0, 0.1);
      border: 1px solid rgba(255, 0, 0, 0.3);
      color: #fff;
    }
    .navbar {
      position: absolute;
      top: 0;
      width: 100%;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand {
      color: #fff !important;
      font-weight: 600;
    }
    .btn-outline-light {
      border: 2px solid #fff;
      color: #fff;
      border-radius: 30px;
      transition: 0.3s;
    }
    .btn-outline-light:hover {
      background: #fff;
      color: #003366;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="index.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Back</a>
    <span class="navbar-brand">Federal University Gashua</span>
  </div>
</nav>

<div class="login-card text-center">
  <div class="mb-3">
    <i class="bi bi-person-circle fs-1 text-light"></i>
  </div>
  <h4 class="mb-3">Welcome Back</h4>
  <p class="text-light mb-4">Login to access your dashboard</p>

  <?php if($error): ?>
    <div class="alert text-center mb-3"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3 text-start">
      <label class="form-label text-light">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
    </div>
    <div class="mb-4 text-start">
      <label class="form-label text-light">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>

  <p class="mt-4 mb-0 text-light">Don't have an account? 
    <a href="register.php" class="text-warning fw-bold text-decoration-none">Register</a>
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
