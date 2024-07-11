<?php
require_once "../.env/env.php";
require_once "../models/Login.php";
require_once "../models/Menu.php";
session_start();
session_regenerate_id(true);

if (isset($_POST['login']) && $_POST['login'] === 'Login') {
    $selectLogin = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE email = '$email'");
    $row = mysqli_fetch_assoc($selectLogin);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            if ($row['id_level'] == $id_level) {
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['id_level'] = $row['id_level'];
                $_SESSION['email'] = $row['email'];

                if ($id_level == 1) {
                    header('Location: ../views/admin/?page=dashboard&login=success');
                    exit;
                } elseif($id_level == 2) {
                    header('Location: ../views/user/?page=dashboard&login=success');
                    exit;
                }
            } else {
                // Handle incorrect user level
                header('Location: ../views/login/?error=wrong_level');
                exit;
            }
        } else {
            // Handle incorrect password
            header('Location: ../views/login/?error=wrong_password');
            exit;
        }
    } else {
        // Handle incorrect email
        header('Location: ../views/login/?error=wrong_email');
        exit;
    }
}
?>
