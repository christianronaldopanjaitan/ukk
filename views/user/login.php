<?php include BASE_PATH . '/views/layout/header.php'; ?>

<h3>Login User</h3>

<?php
if(isset($_SESSION['error'])){
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>

<form method="POST" action="index.php?page=user_login_process">
    <div class="mb-3">
        <label>NIS</label>
            <input 
                type="text"
                name="nis"
                class="form-control"
                placeholder="Masukkan NIS (10-18 angka)"
                pattern="[0-9]{10,18}"
                minlength="10"
                maxlength="18"
                required
            >

    </div>

    <button type="submit" class="btn btn-primary">Login</button>
</form>

<div class="text-center mt-3">
    Belum punya akun?
    <a href="index.php?page=user_register">Register</a>
</div>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>