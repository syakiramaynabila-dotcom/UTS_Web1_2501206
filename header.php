
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) : 'Kantin Kampus'; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
    <h1>🍱 Kantin Kampus</h1>
    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">Keranjang (<?php
            echo isset($_SESSION['keranjang'])
                ? count($_SESSION['keranjang']) : 0;
        ?>)</a>
    </nav>
</header>

<main class="container">
