<?php
session_start();
session_regenerate_id(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
</head>
<body>
    <?php
    if(isset($_SESSION['nama_lengkap']) && isset($_SESSION['email'])){
    ?>
    <a href="views/admin/?page=dashboard">Dashboard</a>
    <?php }else{?>
    <a href="views/login/?page=login">Login</a>
    <a href="">Register</a>
    <a href="<?= (isset($_GET['page']) && $_GET['page'] == 'dashboard') ? 'views/admin/?page=dashboard': (empty($_SESSION['nama_lengkap']) ? 'views/login/?page=login':'views/admin/?page=dashboard' )?>">Admin</a>
    <?php }?>
</body>
</html>