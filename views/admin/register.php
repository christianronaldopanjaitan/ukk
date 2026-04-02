<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Admin.php';

if($_SERVER['REQUEST_METHOD']=="POST"){
    $db = (new Database())->connect();
    $admin = new Admin($db);
    $admin->register($_POST['username'], $_POST['password']);
    header("Location: index.php?page=login_admin");
    exit; // Selalu gunakan exit setelah header redirect
}
?>


<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="card p-4 shadow col-md-5 mx-auto">
    <h4 class="text-center mb-3">Register Admin</h4>
    
    <form method="POST" autocomplete="off">
        
        <div class="mb-3">
            <label class="form-label">Username Baru</label>
            <input type="text" name="username" class="form-control" 
                   placeholder="Username" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" 
                   placeholder="Password" required autocomplete="new-password">
            <small class="text-muted">Gunakan kombinasi password yang kuat.</small>
        </div>

        <button class="btn btn-success w-100">Daftar Sekarang</button>
    </form>
    
    <div class="text-center mt-3">
        Sudah punya akun? 
        <a href="index.php?page=login_admin">Login di sini</a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>