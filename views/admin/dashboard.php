<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ================= CONFIG =================
require_once __DIR__ . '/../../app/config/Config.php';
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Pengaduan.php';

// Redirect kalau belum login
if (!isset($_SESSION['admin'])) {
    header("Location: " . BASE_URL . "index.php?page=login_admin");
    exit;
}

$db = (new Database())->connect();
$pengaduan = new Pengaduan($db);

$countAll = $pengaduan->countAll();

// ================= FILTER =================
$filter = [
    'nis'      => $_GET['nis'] ?? '',
    'kategori' => $_GET['kategori'] ?? '',
    'tanggal'    => $_GET['tanggal'] ?? ''
];

$data  = $pengaduan->getAllAdmin($filter);
$total = count($data);
?>

<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="wrapper">

    <?php include __DIR__ . '/../layout/sidebar_admin.php'; ?>

    <div class="content p-4">

<div class="row mb-4 text-start">

<div class="row mb-3">

    <div class="col-md-3">
        <div class="card bg-dark text-white p-3">
            <h6>Total</h6>
            <h3><?= $countAll['total'] ?? 0 ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white p-3">
            <h6>Menunggu</h6>
            <h3><?= $countAll['menunggu'] ?? 0 ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white p-3">
            <h6>Proses</h6>
            <h3><?= $countAll['proses'] ?? 0 ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            <h6>Selesai</h6>
            <h3><?= $countAll['selesai'] ?? 0 ?></h3>
        </div>
    </div>
</div>

        <div class="card shadow p-4">
            <h5>Data Aspirasi</h5>

            <!-- FILTER -->
            <form method="GET" action="<?= BASE_URL ?>index.php" class="row mb-3">
                <input type="hidden" name="page" value="admin_dashboard">

                <div class="col-md-3">
                    <input type="text"
                           name="nis"
                           class="form-control"
                           placeholder="Filter NIS"
                           value="<?= htmlspecialchars($filter['nis']) ?>">
                </div>

                <div class="col-md-3">
                    <select name="kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="1" <?= $filter['kategori']=='1'?'selected':'' ?>>
                            Aspirasi Sekolah
                        </option>
                        <option value="2" <?= $filter['kategori']=='2'?'selected':'' ?>>
                            Masalah Fasilitas
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="month"
                           name="bulan"
                           class="form-control"
                           value="<?= htmlspecialchars($filter['tanggal']) ?>">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)): ?>
                            <?php $no = 1; foreach ($data as $d): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($d['nis']); ?></td>
                                <td><?= htmlspecialchars($d['ket_kategori'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($d['lokasi'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($d['ket'] ?? '-'); ?></td>
                                <td>
                                    <?php
                                    $status = $d['status'] ?? 'Menunggu';
                                    $badge = 'secondary';
                                    if ($status == 'Proses') $badge = 'warning';
                                    if ($status == 'Selesai') $badge = 'success';
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($d['feedback'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($d['tanggal'] ?? '-'); ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>index.php?page=edit_status&id=<?= $d['id_aspirasi']; ?>"
                                       class="btn btn-warning btn-sm">
                                       Edit
                                    </a>

                                    <a href="<?= APP_URL ?>controllers/PengaduanController.php?action=delete&id=<?= $d['id_aspirasi']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Hapus data?')">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <a href="<?= BASE_URL ?>index.php?page=logout"
               class="btn btn-secondary mt-3">
               Logout
            </a>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>