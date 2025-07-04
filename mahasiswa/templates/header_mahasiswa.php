<?php
// Memulai session di setiap halaman yang membutuhkan otentikasi
session_start();

// Cek apakah pengguna sudah login dan rolenya adalah mahasiswa
// Jika tidak, redirect ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$nama_mahasiswa = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <div class="w-64 bg-gray-800 text-white p-4">
            <h2 class="text-2xl font-bold mb-6">SIMPRAK</h2>
            <nav>
                <a href="dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                <a href="../praktikum_katalog.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Katalog Praktikum</a>
                <a href="praktikum_saya.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Praktikum Saya</a>
                <a href="../logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Logout</a>
            </nav>
        </div>
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Selamat Datang, <?php echo htmlspecialchars($nama_mahasiswa); ?>!</h1>
            </div>