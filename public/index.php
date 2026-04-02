<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/Config.php';

$page = $_GET['page'] ?? 'home';

switch($page){

    // ================= HOME =================
    case 'home':
        require "home.php";
        break;

    // ================= USER =================

    case 'user_login':
        require "../views/user/login.php";
        break;

    case 'user_login_process':
        require "../app/controllers/UserAuthController.php";
        (new UserAuthController())->login();
        break;

    case 'user_register':
        require "../views/user/register.php";
        break;

    case 'user_register_process':
        require "../app/controllers/UserAuthController.php";
        (new UserAuthController())->register();
        break;

    case 'user_dashboard':
        require "../app/controllers/UserAuthController.php";
        (new UserAuthController())->dashboard();
        break;

    case 'user_logout':
        require "../app/controllers/UserAuthController.php";
        (new UserAuthController())->logout();
        break;

    case 'edit_aspirasi':
        require "../views/user/edit_aspirasi.php";
        break;

    case 'update_aspirasi':
        require "../app/controllers/PengaduanController.php";
        (new PengaduanController())->update();
        break;


    // ================= ADMIN =================

    case 'admin_login':
        require "../views/admin/login.php";
        break;

    case 'admin_login_process':
        require "../app/controllers/AdminAuthController.php";
        (new AdminAuthController())->login();
        break;

    case 'admin_register':
        require "../views/admin/register.php";
        break;

    case 'admin_register_process':
        require "../app/controllers/AdminAuthController.php";
        (new AdminAuthController())->register();
        break;

    case 'admin_dashboard':
        if(!isset($_SESSION['admin'])){
            header("Location: index.php?page=admin_login");
            exit;
        }
        require "../views/admin/dashboard.php";
        break;

    case 'edit_status':
        if(!isset($_SESSION['admin'])){
            header("Location: index.php?page=admin_login");
            exit;
        }
        require "../views/admin/edit_status.php";
        break;

    case 'update_status':
        require "../app/controllers/PengaduanController.php";
        (new PengaduanController())->updateStatus();
        break;

    case 'admin_logout':
        require "../app/controllers/AdminAuthController.php";
        (new AdminAuthController())->logout();
        break;


    // ================= DEFAULT =================
    default:
        header("Location: index.php?page=home");
        exit;
}