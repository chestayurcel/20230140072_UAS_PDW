<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = $_POST['kode_praktikum'];
    $nama = $_POST['nama_praktikum'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "INSERT INTO mata_praktikum (kode_praktikum, nama_praktikum, deskripsi) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $kode, $nama, $deskripsi);

    if ($stmt->execute()) {
        header("Location: kelola_praktikum.php?status=sukses&pesan=Data berhasil ditambahkan");
    } else {
        header("Location: kelola_praktikum.php?status=gagal&pesan=Gagal menambahkan data");
    }
    $stmt->close();
    $conn->close();
}
?>