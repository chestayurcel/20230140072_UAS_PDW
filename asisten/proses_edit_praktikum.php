<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $kode = $_POST['kode_praktikum'];
    $nama = $_POST['nama_praktikum'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "UPDATE mata_praktikum SET kode_praktikum = ?, nama_praktikum = ?, deskripsi = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $kode, $nama, $deskripsi, $id);

    if ($stmt->execute()) {
        header("Location: kelola_praktikum.php?status=sukses&pesan=Data berhasil diupdate");
    } else {
        header("Location: kelola_praktikum.php?status=gagal&pesan=Gagal mengupdate data");
    }
    $stmt->close();
    $conn->close();
}
?>