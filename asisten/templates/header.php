<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

$nama_asisten = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Asisten - SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <div class="w-64 bg-gray-900 text-white p-4">
            <h2 class="text-2xl font-bold mb-6">SIMPRAK - Asisten</h2>
            <nav>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                <a href="kelola_praktikum.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Kelola Praktikum</a>
                <a href="kelola_laporan.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Laporan Masuk</a>
                <a href="kelola_pengguna.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Kelola Pengguna</a>
                <a href="../logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mt-8">Logout</a>
            </nav>
        </div>
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Panel Asisten</h1>
                <span class="text-gray-600">Login sebagai: <?php echo htmlspecialchars($nama_asisten); ?></span>
            </div>