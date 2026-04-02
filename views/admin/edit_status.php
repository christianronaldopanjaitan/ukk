<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php?page=admin_login");
    exit;
}

include __DIR__ . '/../layout/header.php';

require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Pengaduan.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    $_SESSION['error'] = "ID tidak valid!";
    header("Location: index.php?page=admin_dashboard");
    exit;
}

$db = (new Database())->connect();
$pengaduan = new Pengaduan($db);

$dataList = $pengaduan->getAllAdmin();
$data = null;

foreach ($dataList as $row) {
    if ($row['id_aspirasi'] == $id) {
        $data = $row;
        break;
    }
}

if (!$data) {
    $_SESSION['error'] = "Data tidak ditemukan!";
    header("Location: index.php?page=admin_dashboard");
    exit;
}
?>

<h4 class="mb-4">
    Edit Status Aspirasi (ID: <?= htmlspecialchars($data['id_aspirasi']); ?>)
</h4>

<div class="card shadow p-4 col-md-6">
    <!-- FIX ACTION -->
    <form method="POST" action="index.php?page=update_status">

        <!-- FIX NAME -->
        <input type="hidden" 
               name="id_aspirasi" 
               value="<?= htmlspecialchars($data['id_aspirasi']); ?>">

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Menunggu" <?= ($data['status'] ?? '') == 'Menunggu' ? 'selected' : ''; ?>>
                    Menunggu
                </option>
                <option value="Proses" <?= ($data['status'] ?? '') == 'Proses' ? 'selected' : ''; ?>>
                    Proses
                </option>
                <option value="Selesai" <?= ($data['status'] ?? '') == 'Selesai' ? 'selected' : ''; ?>>
                    Selesai
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Feedback</label>
            <textarea name="feedback"
                      class="form-control"
                      rows="4"><?= htmlspecialchars($data['feedback'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100">
            Update Status
        </button>

        <a href="index.php?page=admin_dashboard"
           class="btn btn-secondary w-100 mt-2">
           Batal
        </a>

    </form>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
