<?php
class User {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findByNis($nis) {
        $stmt = $this->db->prepare("SELECT * FROM siswa WHERE nis = ?");
        $stmt->execute([$nis]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nis, $kelas) {
        $stmt = $this->db->prepare("INSERT INTO siswa (nis, kelas) VALUES (?, ?)");
        return $stmt->execute([$nis, $kelas]);
    }
}

