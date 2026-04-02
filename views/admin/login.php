<?php
// Mulai session hanya jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include config dan class yang diperlukan
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Admin.php';

$error = ""; // variabel untuk menampung error login

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $db = (new Database())->connect();
    $admin = new Admin($db);

    $data = $admin->login($_POST['username']);

    if ($data && password_verify($_POST['password'], $data['password'])) {
        unset($_SESSION['user']);
        $_SESSION['admin'] = $data['username'];
        header("Location: index.php?page=admin_dashboard");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h4 class="mb-3 text-center">Login Admin</h4>

                <!-- Tampilkan error jika ada -->
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-control" 
                            placeholder="Masukkan username" 
                            required
                            autocomplete="off"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Masukkan password" 
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="mt-3 text-center">
                    <small>
                        Belum punya akun? <a href="index.php?page=admin_register">Registrasi Admin</a><br>
                        Sudah punya akun siswa? <a href="index.php?page=login_siswa">Login sebagai Siswa</a>
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
