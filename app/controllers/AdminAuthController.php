<?php
require_once "../app/config/Database.php";
require_once "../app/models/Admin.php";
require_once "../app/models/Pengaduan.php";

class AdminAuthController {

    // ================= LOGIN ADMIN =================
    public function login() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_login");
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan Password wajib diisi!";
            header("Location: index.php?page=admin_login");
            exit;
        }

        $db = (new Database())->connect();
        $admin = new Admin($db);

        $data = $admin->findByUsername($username);

        if ($data && password_verify($password, $data['password'])) {

            $_SESSION['admin'] = $data['username'];

            header("Location: index.php?page=admin_dashboard");
            exit;

        } else {
            $_SESSION['error'] = "Username atau Password salah!";
            header("Location: index.php?page=admin_login");
            exit;
        }
    }


    // ================= REGISTER ADMIN =================
    public function register() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=admin_register");
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Semua field wajib diisi!";
            header("Location: index.php?page=admin_register");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password minimal 6 karakter!";
            header("Location: index.php?page=admin_register");
            exit;
        }

        $db = (new Database())->connect();
        $admin = new Admin($db);

        if ($admin->findByUsername($username)) {
            $_SESSION['error'] = "Username sudah terdaftar!";
            header("Location: index.php?page=admin_register");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $admin->create($username, $hashedPassword);

        $_SESSION['admin'] = $username;

        header("Location: index.php?page=admin_dashboard");
        exit;
    }


    // ================= DASHBOARD ADMIN =================
    public function dashboard() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['admin'])) {
            header("Location: index.php?page=admin_login");
            exit;
        }

        $db = (new Database())->connect();
        $pengaduan = new Pengaduan($db);

        $data = $pengaduan->getAllAdmin();

        require "../views/admin/dashboard.php";
    }


    // ================= LOGOUT ADMIN =================
    public function logout() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION = [];
        session_destroy();

        header("Location: index.php?page=admin_login");
        exit;
    }
}