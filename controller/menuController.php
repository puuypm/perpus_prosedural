<?php
require_once "../.env/env.php";
require_once "../models/Menu.php";

if(isset($_POST['store']) && $_POST['store'] === "Simpan"){
    // Masukkan data ke tabel `menu`
    $insertMenu = mysqli_query($koneksi, "INSERT INTO menu (id_level, nama_menu, url, icon, num_columns, make_table, created_at, updated_at) VALUES($id_level, '$nama_menu', '$url', '$icon', $num_columns, '$make_table', now(), now())");

    // Periksa apakah data berhasil dimasukkan ke tabel `menu`
    if ($insertMenu) {
        // Dapatkan ID menu yang baru dimasukkan
        $id_menu = mysqli_insert_id($koneksi);

        // Masukkan data ke tabel `menu_column` jika perlu membuat tabel
        if ($make_table == 'yes') {
            for ($i = 1; $i <= $num_columns; $i++) {
                $column_name = 'column' . $i;
                if (isset($_POST[$column_name])) {
                    $nama_column = htmlspecialchars($_POST[$column_name]);
                    $insertColumn = mysqli_query($koneksi, "INSERT INTO menu_column (id_menu, nama_column, created_at, updated_at) VALUES($id_menu, '$nama_column',now(), now())");
                    if (!$insertColumn) {
                        // Jika gagal memasukkan data ke tabel `menu_column`, tampilkan pesan error
                        die("Error inserting data into menu_column: " . mysqli_error($koneksi));
                    }
                }
            }
        }

        // Redirect ke halaman yang diinginkan setelah berhasil menyimpan
        header("location: ../views/admin/?page=menu&&store=success");
        exit;
    } else {
        // Jika gagal memasukkan data ke tabel `menu`, tampilkan pesan error
        die("Error inserting data into menu: " . mysqli_error($koneksi));
    }
}elseif (isset($_POST['update']) && $_POST['update'] === "Edit") {
 // Dapatkan nilai input dari POST atau sumber lain
// $id_level = $_POST['id_level'];
// $nama_menu = $_POST['nama_menu'];
// $url = $_POST['url'];
// $icon = $_POST['icon'];
// $make_table = $_POST['make_table'];
$num_columns = (int)$_POST['num_columns']; // pastikan ini adalah integer
// $id_menu = htmlspecialchars(base64_decode($_GET['ed']));

// Dapatkan nilai num_columns yang ada di database untuk menu ini
$currentNumColumnsQuery = mysqli_query($koneksi, "SELECT num_columns FROM menu WHERE id = $id_menu");
$currentNumColumnsResult = mysqli_fetch_assoc($currentNumColumnsQuery);
$currentNumColumns = (int)$currentNumColumnsResult['num_columns'];

// Logika untuk update data berdasarkan jumlah kolom
if ($num_columns == 0) {
    // Update data di tabel menu
    $updateMenu = mysqli_query($koneksi, "UPDATE menu SET id_level = $id_level, nama_menu = '$nama_menu', url = '$url', icon = '$icon', make_table = '$make_table', num_columns = 0, updated_at = now() WHERE id = $id_menu ");
    // Hapus kolom yang ada di tabel menu_column
    $deleteEdit = mysqli_query($koneksi, "DELETE FROM menu_column WHERE id_menu = $id_menu ");
} elseif ($num_columns == 1) {
    // Update data di tabel menu
    $updateMenu = mysqli_query($koneksi, "UPDATE menu SET id_level = $id_level, nama_menu = '$nama_menu', url = '$url', icon = '$icon', make_table = '$make_table', num_columns = 1, updated_at = now() WHERE id = $id_menu");
    // Hapus kolom yang ada di tabel menu_column
    $deleteEdit = mysqli_query($koneksi, "DELETE FROM menu_column WHERE id_menu = $id_menu ");
    // Loop untuk mengambil dan menyimpan nama kolom
    for ($i = 0; $i < $num_columns; $i++) {
        $column_name = 'column' . ($i + 1);
        if (isset($_POST[$column_name])) {
            $nama_column = mysqli_real_escape_string($koneksi, $_POST[$column_name]);
            $insertMenuCol = mysqli_query($koneksi, "INSERT INTO menu_column (id_menu, nama_column, created_at, updated_at) VALUES ($id_menu, '$nama_column', now(), now())");
        }
    }
} elseif ($num_columns > 1) {
    // Update data di tabel menu
    $updateMenu = mysqli_query($koneksi, "UPDATE menu SET id_level = $id_level, nama_menu = '$nama_menu', url = '$url', icon = '$icon', make_table = '$make_table', num_columns = $num_columns, updated_at = now() WHERE id = $id_menu");

    if ($num_columns < $currentNumColumns) {
        // Hapus kolom yang tidak lagi dibutuhkan
        $deleteColumns = mysqli_query($koneksi, "DELETE FROM menu_column WHERE id_menu = $id_menu");
    }

    // Loop untuk mengambil dan menyimpan nama kolom
    for ($i = 0; $i < $num_columns; $i++) {
        $column_name = 'column' . ($i + 1);
        if (isset($_POST[$column_name])) {
            $nama_column = mysqli_real_escape_string($koneksi, $_POST[$column_name]);
            $columnExistsQuery = mysqli_query($koneksi, "SELECT * FROM menu_column WHERE id_menu = $id_menu AND nama_column = '$nama_column'");
            
            if (mysqli_num_rows($columnExistsQuery) > 0) {
                // Update kolom jika sudah ada
                $updateColumn = mysqli_query($koneksi, "UPDATE menu_column SET nama_column = '$nama_column', updated_at = now() WHERE id_menu = $id_menu AND nama_column = '$nama_column'");
            } else {
                // Tambah kolom jika belum ada
                $insertMenuCol = mysqli_query($koneksi, "INSERT INTO menu_column (id_menu, nama_column, created_at, updated_at) VALUES ($id_menu, '$nama_column', now(), now())");
            }
        }
    }
}
// Redirect ke halaman menu setelah operasi selesai
header('Location: ../views/admin/?page=menu');
exit;
}elseif(isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (isset($_POST['menu_id'])) {
        $menu_id = htmlspecialchars(base64_decode($_POST['menu_id']));
        // Hapus menu dari tabel menu, ketika terhapus maka yang di table menu_column juga ikut terhapus karena sudah di relasi table dengan menggunakan on delete cascade
        $deleteMenu = mysqli_query($koneksi, "DELETE FROM menu WHERE id = $menu_id"); 
        // Redirect ke halaman menu setelah penghapusan selesai
        header('Location: ../views/admin/?page=menu');
        exit;
    }
}
?>


