<?php
session_start();

// Kalau user dah login, redirect ikut role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_kvk/admin_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'guru') {
        header("Location: guru_kvk/guru_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'alumni') {
        header("Location: alumni_kvk/alumni_dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🎓 Alumni Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background:#f8f9fa;
    }
    main {
      flex: 1;
    }
    .hero {
      background: linear-gradient(90deg, #007BFF, #00BFFF);
      color: white;
      padding: 100px 0;
      text-align: center;
    }
    .feature-card {
      transition: transform 0.2s, box-shadow 0.2s;
      border-radius: 15px;
    }
    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow:0 8px 20px rgba(0,0,0,0.2);
    }
    footer {
      background:#212529;
      color:white;
      text-align:center;
      padding:15px 0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🎓 Alumni System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<main>
  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1 class="display-4 fw-bold">Selamat Datang ke Sistem Alumni</h1>
      <p class="lead">Hubungkan Alumni, Guru, dan Admin dalam satu platform</p>
      <a href="login.php" class="btn btn-light btn-lg mt-3">🚀 Log Masuk Sekarang</a>
      <a href="register.php" class="btn btn-outline-light btn-lg mt-3">📝 Daftar Baru</a>
    </div>
  </section>

  <!-- Features Section -->
  <div class="container py-5">
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="card feature-card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title fw-bold">👨‍🎓 Alumni</h5>
            <p class="card-text">Jalin hubungan dengan rakan lama, sertai event, dan kongsikan pengalaman.</p>
            <a href="login.php" class="btn btn-primary">Masuk Sebagai Alumni</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title fw-bold">👩‍🏫 Guru</h5>
            <p class="card-text">Berhubung dengan alumni, umumkan berita & kongsikan info terkini.</p>
            <a href="login.php" class="btn btn-success">Masuk Sebagai Guru</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card feature-card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title fw-bold">🛠️ Admin</h5>
            <p class="card-text">Urus sistem, pantau statistik, dan pastikan kelancaran platform.</p>
            <a href="login.php" class="btn btn-danger">Masuk Sebagai Admin</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="container">
    <p>&copy; 2025 Sistem Alumni. Semua hak cipta terpelihara.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
