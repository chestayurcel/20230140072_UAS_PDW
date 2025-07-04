<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$role = $_POST['role'];
$password = $_POST['password'];

// Logika untuk update password
if (!empty($password)) {
    // Jika password diisi, update semua termasuk password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET nama = ?, email = ?, password = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama, $email, $hashed_password, $role, $id);
} else {
    // Jika password kosong, update tanpa mengubah password
    $sql = "UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $email, $role, $id);
}

if ($stmt->execute()) {
    header("Location: kelola_pengguna.php?status=sukses&pesan=Data pengguna berhasil diupdate.");
} else {
    header("Location: kelola_pengguna.php?status=gagal&pesan=Gagal mengupdate data. Mungkin email sudah digunakan.");
}

$stmt->close();
$conn->close();
?>