<?php
require_once __DIR__ . '/../config/hash.php';

class Admin {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Register admin baru
     * @param string $username
     * @param string $password
     * @return bool true jika berhasil, false jika username sudah ada
     */
    public function register($username, $password) {
        // cek dulu apakah username sudah ada
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute([':username' => $username]);

        if ($stmt->rowCount() > 0) {
            return false; // username sudah ada
        }

        // hash password pakai helper
        $hashed = hashPassword($password);

        $stmt = $this->db->prepare("INSERT INTO admin (username, password) VALUES (:username, :password)");
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashed
        ]);
    }

    /**
     * Login admin
     * @param string $username
     * @return array|null data admin jika ada
     */
    public function login($username) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
