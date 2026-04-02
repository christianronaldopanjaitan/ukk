<?php

class Pengaduan
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // ==========================================
    // AMBIL DATA UNTUK DASHBOARD ADMIN + FILTER
    // ==========================================
public function getAllAdmin($filter = [])
{
    $sql = "SELECT 
                p.id_pelaporan,
                p.nis,
                p.lokasi,
                p.ket,
                p.tanggal,
                k.ket_kategori,
                a.id_aspirasi,
                COALESCE(a.status, 'Menunggu') as status,
                a.feedback
            FROM input_aspirasi p
            LEFT JOIN kategori k 
                ON p.id_kategori = k.id_kategori
            LEFT JOIN aspirasi a 
                ON a.id_aspirasi = p.id_pelaporan
            WHERE 1=1";

    $params = [];

    if (!empty($filter['nis'])) {
        $sql .= " AND p.nis LIKE :nis";
        $params[':nis'] = "%" . $filter['nis'] . "%";
    }

    if (!empty($filter['kategori'])) {
        $sql .= " AND p.id_kategori = :kategori";
        $params[':kategori'] = $filter['kategori'];
    }

    if (!empty($filter['bulan'])) {
        $sql .= " AND DATE_FORMAT(p.tanggal, '%Y-%m') = :bulan";
        $params[':bulan'] = $filter['bulan'];
    }

    $sql .= " ORDER BY p.id_pelaporan DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // ==========================================
    // AMBIL DATA MILIK USER
    // ==========================================
public function getAllUser($nis)
{
    $stmt = $this->db->prepare("
        SELECT 
            p.id_pelaporan,
            p.nis,
            p.lokasi,
            p.ket,
            p.tanggal,
            k.ket_kategori,
            COALESCE(a.status, 'Menunggu') as status,
            a.feedback
        FROM input_aspirasi p
        LEFT JOIN kategori k 
            ON p.id_kategori = k.id_kategori
        LEFT JOIN aspirasi a
            ON a.id_aspirasi = p.id_pelaporan
        WHERE p.nis = ?
        ORDER BY p.id_pelaporan DESC
    ");

    $stmt->execute([$nis]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // ==========================================
    // AMBIL 1 DATA (EDIT)
    // ==========================================
    public function getById($id, $nis = null)
    {
        $sql = "SELECT 
                    p.*,
                    k.ket_kategori,
                    a.status,
                    a.feedback
                FROM input_aspirasi p
                LEFT JOIN kategori k 
                    ON p.id_kategori = k.id_kategori
                LEFT JOIN aspirasi a 
                    ON p.id_pelaporan = a.id_aspirasi
                WHERE p.id_pelaporan = :id";

        if ($nis !== null) {
            $sql .= " AND p.nis = :nis";
        }

        $stmt = $this->db->prepare($sql);

        $params = [':id' => $id];
        if ($nis !== null) {
            $params[':nis'] = $nis;
        }

        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // HITUNG TOTAL
    // ==========================================
public function countAll()
{
    $stmt = $this->db->prepare("
        SELECT 
            COUNT(p.id_pelaporan) as total,

            COUNT(CASE 
                WHEN a.status = 'Menunggu' OR a.status IS NULL 
                THEN 1 
            END) as menunggu,

            COUNT(CASE 
                WHEN a.status = 'Proses' 
                THEN 1 
            END) as proses,

            COUNT(CASE 
                WHEN a.status = 'Selesai' 
                THEN 1 
            END) as selesai

        FROM input_aspirasi p
        LEFT JOIN aspirasi a 
            ON a.id_aspirasi = p.id_pelaporan
    ");

    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    // ==========================================
    // CEK DUPLIKASI
    // ==========================================
    public function exists($id_kategori, $lokasi, $ket)
    {
        $stmt = $this->db->prepare("
            SELECT id_pelaporan 
            FROM input_aspirasi
            WHERE id_kategori = ? AND lokasi = ? AND ket = ?
        ");
        $stmt->execute([$id_kategori, $lokasi, $ket]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // CEK DUPLIKASI USER LAIN
    // ==========================================
    public function checkDuplicateForOtherUser($id_kategori, $lokasi, $ket, $nis)
    {
        $stmt = $this->db->prepare("
            SELECT id_pelaporan 
            FROM input_aspirasi
            WHERE id_kategori = ? 
              AND lokasi = ? 
              AND ket = ? 
              AND nis != ?
        ");
        $stmt->execute([$id_kategori, $lokasi, $ket, $nis]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // TAMBAH ASPIRASI
    // ==========================================
    public function create($data)
    {
        if ($this->exists($data['id_kategori'], $data['lokasi'], $data['ket'])) {
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO input_aspirasi 
            (nis, id_kategori, lokasi, ket, tanggal)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $success = $stmt->execute([
            $data['nis'],
            $data['id_kategori'],
            $data['lokasi'],
            $data['ket']
        ]);

        if (!$success) return false;

        $lastId = $this->db->lastInsertId();

        // Insert otomatis status awal
        $stmt = $this->db->prepare("
            INSERT INTO aspirasi 
            (id_aspirasi, status, feedback) 
            VALUES (?, 'Menunggu', NULL)
        ");

        return $stmt->execute([$lastId]);
    }

    // ==========================================
    // UPDATE USER
    // ==========================================
    public function updateUser($id_pelaporan, $nis, $id_kategori, $lokasi, $ket)
    {
        $stmt = $this->db->prepare("
            UPDATE input_aspirasi
            SET id_kategori = ?, lokasi = ?, ket = ?
            WHERE id_pelaporan = ? AND nis = ?
        ");

        return $stmt->execute([
            $id_kategori,
            $lokasi,
            $ket,
            $id_pelaporan,
            $nis
        ]);
    }

    // ==========================================
    // UPDATE / INSERT STATUS (ADMIN)
    // ==========================================
    public function updateStatus($id, $status, $feedback)
    {
        // Cek apakah sudah ada
        $cek = $this->db->prepare(
            "SELECT id_aspirasi FROM aspirasi WHERE id_aspirasi = ?"
        );
        $cek->execute([$id]);

        if ($cek->rowCount() > 0) {

            // UPDATE
            $query = "UPDATE aspirasi 
                      SET status = :status,
                          feedback = :feedback
                      WHERE id_aspirasi = :id";

        } else {

            // INSERT pertama kali
            $query = "INSERT INTO aspirasi 
                      (id_aspirasi, status, feedback) 
                      VALUES (:id, :status, :feedback)";
        }

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':feedback' => $feedback
        ]);
    }

    // ==========================================
    // DELETE DATA
    // ==========================================
    public function delete($id_pelaporan)
    {
        try {

            $stmt = $this->db->prepare("DELETE FROM aspirasi WHERE id_aspirasi = ?");
            $stmt->execute([$id_pelaporan]);

            $stmt = $this->db->prepare("DELETE FROM input_aspirasi WHERE id_pelaporan = ?");
            $stmt->execute([$id_pelaporan]);

            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
