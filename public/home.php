<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengaduan Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS khusus home -->
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body>

<div class="hero">

    <div class="overlay"></div>

    <div class="content">
        <h1>Sistem Pengaduan Sekolah</h1>
        <p>
            Sampaikan aspirasi dan keluhan Anda dengan mudah, cepat, 
            dan transparan melalui sistem digital sekolah.
        </p>

        <div class="buttons">
            <a href="index.php?page=user_login" class="btn btn-user">
                Login Siswa
            </a>

            <a href="index.php?page=admin_login" class="btn btn-admin">
                Login Admin
            </a>
        </div>
    </div>

</div>

<footer>
    © <?= date('Y'); ?> Sistem Pengaduan Sekolah
</footer>

</body>
</html>