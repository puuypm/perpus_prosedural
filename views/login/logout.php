<?php
session_start();
session_unset();

// Hapus semua variabel sesi
$_SESSION = [];

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hapus sesi dari server
session_destroy();

// Redirect ke halaman login atau halaman utama
header("Location: index.php?page=logout-sukses"); // Gantilah login.php dengan halaman yang sesuai
exit();
?>
