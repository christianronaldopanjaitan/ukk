<?php
/**
 * Fungsi untuk hash password
 * @param string $password
 * @return string hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Fungsi untuk verifikasi password
 * @param string $password plain text
 * @param string $hash hashed password dari DB
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>
