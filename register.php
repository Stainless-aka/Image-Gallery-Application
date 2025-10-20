<?php
session_start();
include 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $chk = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($chk) > 0) {
        $message = '<div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> Email already registered.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>';
    } else {
        $ins = mysqli_query($conn, "INSERT INTO users (name,email,password,role,created_at) VALUES ('$name','$email','$password','$role',NOW())");
        if ($ins) {
            $message = '<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill"></i> Registration successful. <a href="login.php" class="text-decoration-none fw-bold">Login</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-x-circle-fill"></i> Database error: '.mysqli_error($conn).'
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register | Federal University Gashua</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background: url('assets/bg-hero.jpg') no-repeat center center/cover;
  background-attachment: fixed;
  font-family: 'Poppins', sans-serif;
}
.overlay {
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(10px);
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  animation: fadeInUp 0.8s ease;
}
.btn-primary {
  background: linear-gradient(135deg, #003366, #0055cc);
  border: none;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  background: linear-gradient(135deg, #002244, #0044aa);
  transform: translateY(-2px);
}
.form-floating > label {
  color: #555;
}
.form-control:focus {
  box-shadow: 0 0 0 0.2rem rgba(0, 85, 204, 0.25);
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-light shadow-sm fixed-top">
  <div class="container">
    <a href="index.php" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-arrow-left"></i> Back
    </a>
    <span class="navbar-brand fw-semibold text-primary mx-auto">Federal University Gashua</span>
  </div>
</nav>

<div class="overlay">
  <div class="card p-4 p-md-5 w-100" style="max-width: 480px;">
    <div class="text-center mb-4">
      <div class="mb-2">
        <i class="bi bi-person-plus-fill fs-1 text-primary"></i>
      </div>
      <h4 class="fw-bold text-primary">Create an Account</h4>
      <p class="text-muted small">Join the Image Gallery System</p>
    </div>

    <?php echo $message; ?>

    <form method="POST" class="needs-validation" novalidate>
      <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" id="name" placeholder="John Doe" required>
        <label for="name">Full Name</label>
        <div class="invalid-feedback">Please enter your full name.</div>
      </div>
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
        <label for="email">Email address</label>
        <div class="invalid-feedback">Please enter a valid email address.</div>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
        <label for="password">Password</label>
        <div class="invalid-feedback">Please enter your password.</div>
      </div>
      <div class="form-floating mb-4">
        <select name="role" id="role" class="form-select" required>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
        <label for="role">Register as</label>
      </div>
      <button class="btn btn-primary w-100 py-2" type="submit">
        <i class="bi bi-box-arrow-in-right me-1"></i> Register
      </button>
    </form>

    <p class="text-center mt-3 mb-0 small">
      Already have an account?
      <a href="login.php" class="text-decoration-none fw-semibold">Login here</a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap form validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
</body>
</html>
