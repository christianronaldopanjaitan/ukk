<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengaduan Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark custom-navbar shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            🎓 Sistem Pengaduan
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collsapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center">

                <?php if(isset($_SESSION['user']) && ($_GET['page'] ?? '') !== 'user_login'): ?>
                    <span class="text-white me-3 small">
                        👤 NIS: <?= $_SESSION['user']; ?>
                    </span>
                    <a href="index.php?page=user_dashboard" class="btn btn-light btn-sm me-2">Dashboard</a>
                    <a href="index.php?page=user_logout" class="btn btn-danger btn-sm">Logout</a>

                <?php elseif(isset($_SESSION['admin'])): ?>
                    <span class="text-white me-3 small">
                        🔐 Admin
                    </span>
                    <a href="index.php?page=admin_dashboard" class="btn btn-light btn-sm me-2">Dashboard</a>
                    <a href="index.php?page=admin_logout" class="btn btn-danger btn-sm">Logout</a>

                <?php else: ?>
                    <a href="index.php?page=user_login" class="btn btn-outline-light btn-sm me-2">Login User</a>
                    <a href="index.php?page=admin_login" class="btn btn-warning btn-sm">Login Admin</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">