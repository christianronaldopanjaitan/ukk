<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('BASE_URL', 'http://localhost/aspirasi/public/');

function redirect($page) {
    header("Location: " . BASE_URL . "index.php?page=$page");
    exit;
}

function authUser() {
    if (!isset($_SESSION['nis'])) {
        redirect('login');
    }
}

function authAdmin() {
    if (!isset($_SESSION['admin'])) {
        redirect('login-admin');
    }
}

function logout() {
    session_destroy();
    redirect('login');
}
