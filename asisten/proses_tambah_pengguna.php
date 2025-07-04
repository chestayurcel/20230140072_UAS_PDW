<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Cek apakah email sudah ada
$sql_check = "SELECT id FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Tambahkan pesan error ke halaman kelola pengguna
    header("Location: kelola_pengguna.php?status=gagal&pesan=Email sudah terdaftar.");
    exit;
}
$stmt_check->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert data baru
$sql_insert = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ssss", $nama, $email, $hashed_password, $role);

if ($stmt_insert->execute()) {
    header("Location: kelola_pengguna.php?status=sukses&pesan=Pengguna baru berhasil ditambahkan.");
} else {
    header("Location: kelola_pengguna.php?status=gagal&pesan=Gagal menambahkan pengguna.");
}

$stmt_insert->close();
$conn->close();
?>