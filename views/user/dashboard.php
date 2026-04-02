<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="wrapper">

    <?php include __DIR__ . '/../layout/sidebar_user.php'; ?>

    <div class="content p-4">

        <h4 class="mb-4">Dashboard Siswa (NIS: <?= $_SESSION['user']; ?>)</h4>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- FORM INPUT -->
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h5>Input Aspirasi</h5>

                    <form method="POST" action="../app/controllers/PengaduanController.php?action=store">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Aspirasi Sekolah</option>
                                <option value="2">Masalah Fasilitas</option>
                                <option value="3">Lain-lain</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Kejadian</label>
                            <input 
                                type="text" 
                                name="lokasi" 
                                class="form-control" 
                                placeholder="Contoh: Kelas 10 AKL / Kantin" 
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Aspirasi</label>
                            <textarea 
                                name="ket" 
                                class="form-control" 
                                rows="4"
                                placeholder="Jelaskan detail aspirasi Anda..." 
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Kirim Aspirasi
                        </button>
                    </form>
                </div>
            </div>

            <!-- DATA TABEL -->
            <div class="col-md-7">
                <div class="card shadow p-4">
                    <h5>Data Aspirasi Saya</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)): ?>
                                    <?php foreach($data as $d): ?>
                                        <tr>
                                            <td><?= $d['id_pelaporan']; ?></td>
                                            <td><?= $d['ket_kategori'] ?? ''; ?></td>
                                            <td><?= $d['lokasi'] ?? ''; ?></td>
                                            <td><?= $d['ket'] ?? ''; ?></td>
                                            <td><?= $d['status'] ?? 'Menunggu'; ?></td>
                                            <td>
                                                <?= !empty($d['tanggal']) 
                                                    ? date('d-m-Y', strtotime($d['tanggal'])) 
                                                    : ''; ?>
                                            </td>
                                            <td>
                                                <a href="index.php?page=edit_aspirasi&id=<?= $d['id_pelaporan']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                   Edit
                                                </a>

                                                <a href="../app/controllers/PengaduanController.php?action=delete&id=<?= $d['id_pelaporan']; ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Hapus aspirasi ini?')">
                                                   Hapus
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Belum ada aspirasi.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <a href="index.php?page=logout" class="btn btn-danger mt-2">
                        Logout
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>