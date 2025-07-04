<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$laporan_id = $_POST['laporan_id'];
$nilai = $_POST['nilai'];
$feedback = $_POST['feedback'];
$status = 'dinilai';

$sql = "UPDATE laporan SET nilai = ?, feedback = ?, status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $nilai, $feedback, $status, $laporan_id);

if ($stmt->execute()) {
    header("Location: kelola_laporan.php?status_nilai=sukses&pesan=Nilai berhasil disimpan.");
} else {
    header("Location: kelola_laporan.php?status_nilai=gagal&pesan=Gagal menyimpan nilai.");
}

$stmt->close();
$conn->close();
?>