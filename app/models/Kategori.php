<?php
class Kategori {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil semua kategori
    public function getAllArray() {
        $stmt = $this->db->prepare("SELECT * FROM kategori ORDER BY ket_kategori ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil kategori berdasarkan id
    public function getById($id_kategori) {
        $stmt = $this->db->prepare("SELECT * FROM kategori WHERE id_kategori = ? LIMIT 1");
        $stmt->execute([$id_kategori]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CRUD kategori (admin)
    public function create($ket_kategori) {
        $stmt = $this->db->prepare("INSERT INTO kategori (ket_kategori) VALUES (?)");
        return $stmt->execute([$ket_kategori]);
    }

    public function update($id_kategori, $ket_kategori) {
        $stmt = $this->db->prepare("UPDATE kategori SET ket_kategori = ? WHERE id_kategori = ?");
        return $stmt->execute([$ket_kategori, $id_kategori]);
    }

    public function delete($id_kategori) {
        $stmt = $this->db->prepare("DELETE FROM kategori WHERE id_kategori = ?");
        return $stmt->execute([$id_kategori]);
    }
}
