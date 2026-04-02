<?php
require_once "../app/config/Database.php";
require_once "../app/models/User.php";
require_once "../app/models/Pengaduan.php";
require_once "../app/models/Kategori.php";

class UserAuthController {

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user_login");
            exit;
        }

        $nis = trim($_POST['nis']);
        if (!preg_match('/^[0-9]{10,18}$/', $nis)) {
            $_SESSION['error'] = "NIS harus angka 10-18 digit!";
            header("Location: index.php?page=user_login");
            exit;
        }

        $db = (new Database())->connect();
        $user = new User($db);
        $data = $user->findByNis($nis);

        if ($data) {
            $_SESSION['user'] = $data['nis'];
            header("Location: index.php?page=user_dashboard");
            exit;
        } else {
            $_SESSION['error'] = "NIS tidak ditemukan!";
            header("Location: index.php?page=user_login");
            exit;
        }
    }

    public function register() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=user_register");
            exit;
        }

        $nis  = trim($_POST['nis']);
        $nama = trim($_POST['nama']);

        if (!preg_match('/^[0-9]{10,18}$/', $nis)) {
            $_SESSION['error'] = "NIS harus angka 10-18 digit!";
            header("Location: index.php?page=user_register");
            exit;
        }

        if (empty($nama)) {
            $_SESSION['error'] = "Nama tidak boleh kosong!";
            header("Location: index.php?page=user_register");
            exit;
        }

        $db = (new Database())->connect();
        $user = new User($db);

        if ($user->findByNis($nis)) {
            $_SESSION['error'] = "NIS sudah terdaftar!";
            header("Location: index.php?page=user_register");
            exit;
        }

        $user->create($nis, $nama);
        $_SESSION['user'] = $nis;

        header("Location: index.php?page=user_dashboard");
        exit;
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=user_login");
            exit;
        }

        $db = (new Database())->connect();
        $pengaduan = new Pengaduan($db);
        $kategori  = new Kategori($db);

        $data = $pengaduan->getAllUser($_SESSION['user']);
        $listKategori = $kategori->getAllArray();

        require "../views/user/dashboard.php";
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?page=user_login");
        exit;
    }
}
