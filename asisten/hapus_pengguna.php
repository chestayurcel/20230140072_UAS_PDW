<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$id_to_delete = $_GET['id'];

// Keamanan: Mencegah admin menghapus akunnya sendiri
if ($id_to_delete == $_SESSION['user_id']) {
    header("Location: kelola_pengguna.php?status=gagal&pesan=Anda tidak dapat menghapus akun Anda sendiri!");
    exit;
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_to_delete);

if ($stmt->execute()) {
    header("Location: kelola_pengguna.php?status=sukses&pesan=Pengguna berhasil dihapus.");
} else {
    header("Location: kelola_pengguna.php?status=gagal&pesan=Gagal menghapus pengguna.");
}

$stmt->close();
$conn->close();
?>