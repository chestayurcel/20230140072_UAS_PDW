<?php
require_once 'config.php';
session_start();

// 1. Pastikan pengguna adalah mahasiswa yang sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: login.php?error=accessdenied");
    exit;
}

// 2. Pastikan ID praktikum ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: praktikum_katalog.php?status=gagal&error=ID praktikum tidak valid.");
    exit;
}

$mahasiswa_id = $_SESSION['user_id'];
$praktikum_id = $_GET['id'];

// 3. Cek apakah mahasiswa sudah terdaftar di praktikum ini
$sql_check = "SELECT id FROM pendaftaran_praktikum WHERE mahasiswa_id = ? AND praktikum_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $mahasiswa_id, $praktikum_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Jika sudah terdaftar, kembalikan dengan pesan error
    $stmt_check->close();
    header("Location: praktikum_katalog.php?status=gagal&error=Anda sudah terdaftar di praktikum ini.");
    exit;
}
$stmt_check->close();

// 4. Jika belum terdaftar, masukkan data pendaftaran baru
$sql_insert = "INSERT INTO pendaftaran_praktikum (mahasiswa_id, praktikum_id) VALUES (?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ii", $mahasiswa_id, $praktikum_id);

if ($stmt_insert->execute()) {
    // Jika berhasil, redirect dengan pesan sukses
    header("Location: praktikum_katalog.php?status=sukses");
} else {
    // Jika gagal, redirect dengan pesan error
    header("Location: praktikum_katalog.php?status=gagal&error=Terjadi kesalahan saat mendaftar.");
}

$stmt_insert->close();
$conn->close();
exit;