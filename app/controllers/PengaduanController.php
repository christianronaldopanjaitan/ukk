<?php
// Gunakan __DIR__ supaya include selalu benar
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Pengaduan.php';

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

class PengaduanController {
    private $pengaduan;

    public function __construct() {
        $db = (new Database())->connect();
        $this->pengaduan = new Pengaduan($db);
    }

    // ================= STORE ASPIRASI (USER) =================
    public function store() {
        if(!isset($_SESSION['user'])){
            header("Location: ../../public/index.php?page=user_login");
            exit;
        }

        $nis         = $_SESSION['user'];
        $id_kategori = $_POST['kategori'] ?? '';
        $lokasi      = trim($_POST['lokasi'] ?? '');
        $ket         = trim($_POST['ket'] ?? '');

        // Cek duplikasi di user lain
        $duplicate = $this->pengaduan->checkDuplicateForOtherUser($id_kategori, $lokasi, $ket, $nis);
        if($duplicate){
            $_SESSION['error'] = "Aspirasi ini sudah pernah diinput oleh user lain!";
            header("Location: ../../public/index.php?page=user_dashboard");
            exit;
        }

        $success = $this->pengaduan->create([
            'nis' => $nis,
            'id_kategori' => $id_kategori,
            'lokasi' => $lokasi,
            'ket' => $ket
        ]);

        if($success){
            $_SESSION['success'] = "Aspirasi berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan aspirasi!";
        }

        header("Location: ../../public/index.php?page=user_dashboard");
        exit;
    }

    // ================= UPDATE ASPIRASI (USER) =================
    public function update() {
        if(!isset($_SESSION['user'])){
            header("Location: index.php?page=user_login");
            exit;
        }

        $id_pelaporan = $_POST['id_pelaporan'] ?? 0;
        $nis          = $_SESSION['user'];
        $id_kategori  = $_POST['kategori'] ?? '';
        $lokasi       = trim($_POST['lokasi'] ?? '');
        $ket          = trim($_POST['ket'] ?? '');

        $success = $this->pengaduan->updateUser(
            $id_pelaporan, 
            $nis, 
            $id_kategori, 
            $lokasi, 
            $ket
        );

        if($success){
            $_SESSION['success'] = "Aspirasi berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui aspirasi!";
        }

        header("Location: index.php?page=user_dashboard");
        exit;
    }

    // ================= UPDATE STATUS ADMIN =================
    public function updateStatus()
    {
        // Harus POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/index.php?page=admin_dashboard");
            exit;
        }

        // Cek login admin
        if(!isset($_SESSION['admin'])){
            header("Location: index.php?page=admin_login");
            exit;
        }

        $id       = $_POST['id_aspirasi'] ?? 0;
        $status   = $_POST['status'] ?? '';
        $feedback = $_POST['feedback'] ?? null;

        if(!$id || !$status){
            $_SESSION['error'] = "Data tidak lengkap!";
            header("Location: index.php?page=admin_dashboard");
            exit;
        }

        $success = $this->pengaduan
            ->updateStatus($id, $status, $feedback);

        $_SESSION[$success ? 'success' : 'error'] =
            $success ? "Status berhasil diperbarui!"
                     : "Gagal memperbarui status!";

        header("Location: index.php?page=admin_dashboard");
        exit;
    }

    // ================= DELETE ASPIRASI =================
    public function delete() {
        if(!isset($_SESSION['user']) && !isset($_SESSION['admin'])){
            header("Location: ../../public/index.php?page=user_login");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if(!$id){
            $_SESSION['error'] = "ID aspirasi tidak valid!";
            header("Location: ../../public/index.php?page=user_dashboard");
            exit;
        }

        // Hapus data dan reindex ID
        $this->pengaduan->delete($id);

        $_SESSION['success'] = "Aspirasi berhasil dihapus dan ID direindex!";

        // Redirect ke dashboard sesuai role
        if(isset($_SESSION['admin'])){
            header("Location: ../../public/index.php?page=admin_dashboard");
        } else {
            header("Location: ../../public/index.php?page=user_dashboard");
        }
        exit;
    }
}

// ================= EKSEKUSI CONTROLLER =================
$controller = new PengaduanController();

if(isset($_GET['action'])){
    switch($_GET['action']){
        case "store":
            $controller->store();
            break;
        case "update":
            $controller->update();
            break;
        case "updateStatus":
            $controller->updateStatus();
            break;        
        case "delete":
            $controller->delete();
            break;
        default:
            header("Location: ../../public/index.php");
            exit;
    }
}
