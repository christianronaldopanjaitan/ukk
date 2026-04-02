<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include BASE_PATH . '/views/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="card shadow">
            <div class="card-body">

                <h4 class="text-center mb-4">Register User</h4>

                <?php
                if(isset($_SESSION['error'])){
                    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                    unset($_SESSION['error']);
                }
                ?>

                <form method="POST" action="index.php?page=user_register_process">

                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input 
                            type="text"
                            name="nis"
                            class="form-control"
                            placeholder="Masukkan NIS (10-18 angka)"
                            pattern="[0-9]{10,18}"
                            minlength="10"
                            maxlength="18"
                            required
                            autocomplete="off"
                        >
                        <small class="text-muted">Hanya angka, 10-18 digit</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">KELAS</label>
                        <input 
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Kelas Kamu Di mana?"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Daftar
                    </button>

                </form>

                <div class="text-center mt-3">
                    <small>
                        Sudah punya akun?
                        <a href="index.php?page=user_login">Login di sini</a>
                    </small>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include BASE_PATH . '/views/layout/footer.php'; ?>
