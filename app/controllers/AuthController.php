<?php
require_once "../app/config/Database.php";
require_once "../app/models/Siswa.php";
require_once "../app/models/Admin.php";

class AuthController {

    public function loginSiswa() {
        $db = (new Database())->connect();
        $siswa = new Siswa($db);

        $data = $siswa->login($_POST['nis']);

        if($data){
            $_SESSION['user'] = $data['nis'];
            header("Location: index.php?page=user_dashboard");
        } else {
            echo "Login gagal!";
        }
    }

    public function loginAdmin() {
        $db = (new Database())->connect();
        $admin = new Admin($db);

        $data = $admin->login($_POST['username']);

        if($data && password_verify($_POST['password'],$data['password'])){
            $_SESSION['admin']=$data['username'];
            header("Location: index.php?page=admin_dashboard");
        } else {
            echo "Login gagal!";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }
}
