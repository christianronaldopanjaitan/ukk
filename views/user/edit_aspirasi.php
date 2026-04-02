<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=user_login");
    exit;
}

require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Pengaduan.php';

$db = (new Database())->connect();
$pengaduan = new Pengaduan($db);

// Validasi ID
$id_pelaporan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_pelaporan <= 0) {
    $_SESSION['error'] = "ID aspirasi tidak valid!";
    header("Location: index.php?page=user_dashboard");
    exit;
}

// Ambil data
$data = $pengaduan->getById($id_pelaporan);

if (!$data || $data['nis'] != $_SESSION['user']) {
    $_SESSION['error'] = "Aspirasi tidak ditemukan atau bukan milik Anda!";
    header("Location: index.php?page=user_dashboard");
    exit;
}

include __DIR__ . '/../layout/header.php';
?>

<h4 class="mb-4">Edit Aspirasi (ID: <?= $data['id_pelaporan']; ?>)</h4>

<div class="card shadow p-4 col-md-6">
    <form method="POST" action="index.php?page=update_aspirasi">

        <!-- WAJIB ADA -->
        <input type="hidden" name="id_pelaporan" 
               value="<?= $data['id_pelaporan']; ?>">

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="1" <?= ($data['id_kategori']==1?'selected':''); ?>>Aspirasi Sekolah</option>
                <option value="2" <?= ($data['id_kategori']==2?'selected':''); ?>>Masalah Fasilitas</option>
                <option value="3" <?= ($data['id_kategori']==3?'selected':''); ?>>Lain-lain</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi Kejadian</label>
            <input type="text" name="lokasi" class="form-control" 
                value="<?= htmlspecialchars($data['lokasi']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan Aspirasi</label>
            <textarea name="ket" class="form-control" rows="4" required><?= htmlspecialchars($data['ket']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-warning w-100">Update Aspirasi</button>
        <a href="index.php?page=user_dashboard" class="btn btn-secondary w-100 mt-2">Batal</a>

    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
