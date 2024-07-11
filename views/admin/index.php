<?php
include "../layouts/inc/asset.php";
require_once "../../.env/env.php";
session_start();
session_regenerate_id(true);

if (empty($_SESSION['email']) && empty($_SESSION['nama_lengkap']) && empty($_SESSION['id_level'])) {
    header('location: ../login/?page=login');
    exit;
}

$selectMenu = mysqli_query($koneksi, "SELECT menu.*, level.id as level_id, level.nama_level FROM menu JOIN level on menu.id_level = level.id");
$menus = mysqli_fetch_all($selectMenu, MYSQLI_ASSOC);
$selectMenuColom = mysqli_query($koneksi, "SELECT * FROM menu_column");
$menuColumns = mysqli_fetch_all($selectMenuColom, MYSQLI_ASSOC);


// Periksa apakah parameter 'ed' ada dalam URL
if (isset($_GET['ed'])) {
    // Ambil ID menu yang ingin di-edit dari parameter URL dan lakukan decode base64 serta escape
    $ed = htmlspecialchars(base64_decode($_GET['ed']));

    // Escape ID untuk keamanan
    $ed = mysqli_real_escape_string($koneksi, $ed);

    // Tampilkan nilai $ed untuk debugging
    // echo $ed;

    // Lakukan query untuk mengambil data menu berdasarkan ID
    $query = "SELECT menu.*, level.id as level_id, level.nama_level 
              FROM menu 
              JOIN level ON menu.id_level = level.id 
              WHERE menu.id = '$ed'";
    $selectMenuEdt = mysqli_query($koneksi, $query);

    // Periksa apakah query berhasil dieksekusi
    if ($selectMenuEdt) {
        // Ambil satu baris data menu sebagai array asosiatif
        $menuEdt = mysqli_fetch_assoc($selectMenuEdt);

        // Tampilkan hasil query untuk debugging
        // var_dump($menuEdt);

        // Periksa apakah ada data yang ditemukan
        if ($menuEdt) {
            // Tampilkan URL dari data menu
            $urls = htmlspecialchars($menuEdt['url']);
        } else {
            echo "Menu tidak ditemukan.";
        }
    } else {
        // Tampilkan pesan error jika query gagal
        echo "Query error: " . mysqli_error($koneksi);
    }
} else {
    // Tangani kasus di mana parameter 'ed' tidak ada
    // echo "Parameter 'ed' tidak ditemukan dalam URL.";
    // Anda juga bisa mengarahkan pengguna ke halaman lain atau melakukan tindakan lainnya
    // header('Location: halaman_lain.php');
    // exit();

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <?php include ADMIN . "inc/css.php"; ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include ADMIN . "inc/sidebar.php"; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include ADMIN . "inc/navbar.php"; ?>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <?php if (isset($_GET['page']) && $_GET['page'] === "dashboard") { ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <?php } else if (isset($_GET['page']) && $_GET['page'] === "menu") { ?>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Menu</h1>
                    </div>

                    <div class="row">

                        <!-- Card Menu nya -->
                        <div class="col-xl-12 col-md-11 mb-4">
                            <div class="card">
                                <h5 class="card-header">Settings Menu</h5>
                                <div class="card-body">
                                    <a href="?page=createMenu" class="btn btn-primary btn-sm mb-4">ADD</a>
                                    <div class="table table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Level</th>
                                                    <th>Menu</th>
                                                    <th>URL</th>
                                                    <th>Icon</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <?php
                                                $no = 1;
                                                foreach ($menus as $menu) { ?>
                                            <tbody>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $menu['nama_level'] ?></td>
                                                    <td><?= $menu['nama_menu'] ?></td>
                                                    <td><?= $menu['url'] ?></td>
                                                    <td><i class="<?= $menu['icon'] ?>"> </i><?= $menu['icon'] ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-success btn-sm"
                                                            href="?page=editMenu&ed=<?= htmlspecialchars(base64_encode($menu['id'])) ?>">Edit</a>
                                                        <form action="../../controller/menuController.php"
                                                            method="POST">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="menu_id"
                                                                value="<?= htmlspecialchars(base64_encode($menu['id'])) ?>">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Delete</button>
                                                        </form>

                                                    </td>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else if (isset($_GET['page']) && $_GET['page'] === "createMenu") { ?>
                    <div class="card-header">ADD MENU</div>
                    <div class="card-body">
                        <form action="../../controller/menuController.php" method="post">
                            <div class="form-group">
                                <label for="id_level">Jurusan</label>
                                <select name="id_level" id="id_level" class="form-control" required>
                                    <option value="">--Pilih Level--</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nama_menu">Nama Menu</label>
                                <input type="text" name="nama_menu" id="nama_menu" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="url">Nama URL</label>
                                <input type="text" name="url" id="url" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="icon">Icons</label>
                                <select name="icon" id="icon" class="form-control" required>
                                    <option value="">--Pilih Icons--</option>
                                    <option value="fas fa-fw fa-tachometer-alt">Tachometer <i
                                            class="fas fa-fw fa-tachometer-alt"></i></option>
                                    <option value="fas fa-fw fa-cog">Cog <i class="fas fa-fw fa-cog"></i></option>
                                    <option value="fas fa-fw fa-wrench">Wrench <i class="fas fa-fw fa-wrench"></i>
                                    </option>
                                    <option value="fas fa-fw fa-folder">Folder <i class="fas fa-fw fa-folder"></i>
                                    </option>
                                    <option value="fas fa-fw fa-chart-area">Chart Area <i
                                            class="fas fa-fw fa-chart-area"></i></option>
                                    <option value="fas fa-fw fa-table">Table <i class="fas fa-fw fa-table"></i></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="table">Apakah ingin buat Table ?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="make_table" id="make_table1"
                                        value="yes" onclick="toggleColumnInput(true)">
                                    <label class="form-check-label" for="make_table1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="make_table" id="make_table2"
                                        value="no" onclick="toggleColumnInput(false)">
                                    <label class="form-check-label" for="make_table2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="columnInput" style="display: none;">
                                <label for="num_columns">Isi Jumlah Kolom:</label>
                                <input class="form-control" type="number" name="num_columns" id="num_columns" min="0">
                                <br><br>
                            </div>
                            <!-- Container untuk input kolom dinamis -->
                            <div id="columnInputsContainer" class="mb-3"></div>
                            <div class="form-group mb-3">
                                <input type="submit" name="store" class="btn btn-primary" value="Simpan">
                                <a href="?page=menu" class="btn btn-danger">Kembali</a>
                            </div>
                        </form>
                    </div>
                    <!-- Fungsi skrip untuk menampilkan atau menyembunyikan input jumlah kolom -->
                    <script src="../../public/js/showTables.js"></script>
                    <?php } else if (isset($_GET['page']) && $_GET['page'] === "editMenu") { ?>
                    <div class="card-header">EDIT MENU</div>
                    <div class="card-body">
                        <form action="../../controller/menuController.php" method="post">
                            <input type="hidden" name="menuIdEdt"
                                value="<?= htmlspecialchars((base64_decode($_GET['ed']))) ?>">
                            <?php ?>
                            <div class="form-group">
                                <label for="id_level">Jurusan</label>
                                <select name="id_level" id="id_level" class="form-control" required>
                                    <option value="">--Pilih Level--</option>
                                    <option value="1">Admin</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nama_menu">Nama Menu</label>
                                <input type="text" name="nama_menu" id="nama_menu" class="form-control"
                                    value="<?= (isset($menuEdt['nama_menu'])) ? $menuEdt['nama_menu'] : 'Belum Ada Nama Menu' ?>">
                            </div>
                            <div class="form-group">
                                <label for="url">Nama URL</label>
                                <input type="text" name="url" id="url" class="form-control"
                                    value="<?= (isset($menuEdt['url'])) ? $menuEdt['url'] : 'Belum Ada URL' ?>">
                            </div>
                            <div class="form-group">
                                <label for="icon">Icons</label>
                                <select name="icon" id="icon" class="form-control">
                                    <option value="">--Pilih Icons--</option>
                                    <option value="fas fa-fw fa-tachometer-alt"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-tachometer-alt') ? 'selected' : '' ?>>
                                        Tachometer <i class="fas fa-fw fa-tachometer-alt"></i></option>
                                    <option value="fas fa-fw fa-cog"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-cog') ? 'selected' : '' ?>>
                                        Cog <i class="fas fa-fw fa-cog"></i></option>
                                    <option value="fas fa-fw fa-wrench"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-wrench') ? 'selected' : '' ?>>
                                        Wrench <i class="fas fa-fw fa-wrench"></i></option>
                                    <option value="fas fa-fw fa-folder"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-folder') ? 'selected' : '' ?>>
                                        Folder <i class="fas fa-fw fa-folder"></i></option>
                                    <option value="fas fa-fw fa-chart-area"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-chart-area') ? 'selected' : '' ?>>
                                        Chart Area <i class="fas fa-fw fa-chart-area"></i></option>
                                    <option value="fas fa-fw fa-table"
                                        <?= (isset($menuEdt['icon']) && $menuEdt['icon'] === 'fas fa-fw fa-table') ? 'selected' : '' ?>>
                                        Table <i class="fas fa-fw fa-table"></i></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="table">Apakah ingin buat Table ?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="make_table" id="make_table1"
                                        value="yes" onclick="toggleColumnInput(true)" required>
                                    <label class="form-check-label" for="make_table1">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="make_table" id="make_table2"
                                        value="no" onclick="toggleColumnInput(false)" required>
                                    <label class="form-check-label" for="make_table2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="columnInput" style="display: none;">
                                <label for="num_columns">Isi Jumlah Kolom:</label>
                                <input class="form-control" type="number" name="num_columns" id="num_columns" min="0"
                                    value="<?= (isset($menuEdt['num_columns'])) ? $menuEdt['num_columns'] : 'Belum Ada URL' ?>"
                                    required>
                                <br><br>
                            </div>
                            <!-- Container untuk input kolom dinamis -->
                            <div id="columnInputsContainer" class="mb-3"></div>
                            <div class="form-group mb-3">
                                <input type="submit" name="update" class="btn btn-primary" value="Edit">
                                <a href="?page=menu" class="btn btn-danger">Kembali</a>
                            </div>
                        </form>
                    </div>
                    <!-- Fungsi skrip untuk menampilkan atau menyembunyikan input jumlah kolom -->
                    <script src="../../public/js/showTables.js"></script>
                    <?php
                    } else if (isset($_GET['page']) && $_GET['page'] != "createMenu") {
                        // Memunculkan menu yang baru diinputkan
                        foreach ($menus as $key => $menu) {
                            // Ambil nilai parameter 'page' dari URL
                            $page = $_GET['page'];
                            // Ambil URL saat ini
                            $url = $menu['url'];
                            // Regex untuk mengambil parameter 'page'
                            preg_match('/[?&]page=([^&]+)/', $url, $matches);
                            // Ambil nilai parameter 'page'
                            $page_url = isset($matches[1]) ? $matches[1] : '';
                            // Periksa apakah halaman saat ini sama dengan nama menu
                            if (isset($page) && $page === $page_url) {
                        ?>
                    <!-- Konten yang sesuai dengan menu -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?= $menu['nama_menu'] ?></h1>
                    </div>
                    <div class="row">

                        <!-- Card Menu nya -->
                        <div class="col-xl-12 col-md-11 mb-4">
                            <div class="card">
                                <div class="card-header">Settings <?= $menu['nama_menu'] ?></div>
                                <div class="card-body">
                                    <a href="#" class="btn btn-primary btn-sm mb-4">ADD</a>
                                    <div class="table table-responsive">
                                        <?php
                                                    $tableHTML = '';
                                                    // Cek apakah user memilih buat tabel
                                                    if (isset($menu['make_table']) && $menu['make_table'] == 'yes') {
                                                        // Ambil jumlah kolom dari input user
                                                        $numColumns = isset($menu['num_columns']) ? (int)$menu['num_columns'] : 0;

                                                        // Buat tabel HTML sesuai dengan jumlah kolom
                                                        if ($numColumns > 0) {
                                                            $tableHTML .= '<table class="table table-bordered">';
                                                            $tableHTML .= '<thead>';
                                                            $tableHTML .= '<tr>';

                                                            // Loop untuk menambahkan nama kolom dari menu_column
                                                            foreach ($menuColumns as $menuColumn) {
                                                                if ($menu['id'] == $menuColumn['id_menu']) { // Sesuaikan dengan id menu yang sedang diedit
                                                                    $tableHTML .= '<th>' . $menuColumn['nama_column'] . '</th>';
                                                                }
                                                            }

                                                            // Tambahkan kolom Action di bagian paling kanan
                                                            $tableHTML .= '<th>Action</th>';
                                                            $tableHTML .= '</tr>';
                                                            $tableHTML .= '</thead>';
                                                            // Tambahkan baris kosong sebagai contoh
                                                            $tableHTML .= '<tbody>';
                                                            $tableHTML .= '<tr>';
                                                            for ($i = 0; $i < $numColumns; $i++) {
                                                                $tableHTML .= '<td></td>';
                                                            }
                                                            // Tambahkan tombol Edit dan Delete di kolom Action
                                                            $tableHTML .= '<td><a class="btn btn-success btn-sm" href="#">Edit</a> <a class="btn btn-danger btn-sm" href="#">Delete</a></td>';
                                                            $tableHTML .= '</tr>';
                                                            $tableHTML .= '</tbody>';
                                                            $tableHTML .= '</table>';
                                                        }
                                                    }

                                                    // Tampilkan tabel HTML jika tidak kosong
                                                    if (!empty($tableHTML)) {
                                                        echo $tableHTML;
                                                    }
                                                    ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Assuming $menua is an array of menu items and $menuColumns is an array of menu columns

                    // Check if 'page' is set in the URL and matches $menua['id']
                    if (isset($_GET['page']) && $_GET['page'] == $menua['id']) {
                        foreach ($menuColumns as $menuColumn) {
                            // Check if the current menu item's ID matches with $menuColumn['id_menu']
                            if ($menu['id'] == $menuColumn['id_menu'] && $menuColumn['nama_column'] != 'No' && $menuColumn) {
                                echo "<div class='form-group'>";
                                echo "<label for='" . $menuColumn['nama_column'] . $menuColumn['id'] . "'>" . $menuColumn['nama_column'] . "</label>";
                                // Add your form input fields or other HTML here if needed
                                echo "</div>";
                            }
                        }
                    }
                    ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include ADMIN . "inc/footer.php"; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include ADMIN . "inc/modal-logout.php"; ?>

    <?php include ADMIN . "inc/js.php"; ?>
</body>

</html>