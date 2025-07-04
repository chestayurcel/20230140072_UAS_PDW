<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || !isset($_GET['id'])) {
    header("Location: ../login.php");
    exit;
}

$modul_id = $_GET['id'];
$praktikum_id = $_GET['praktikum_id']; // Untuk redirect kembali
$redirect_url = "kelola_modul.php?praktikum_id=$praktikum_id";

// 1. Ambil nama file materi untuk dihapus dari server
$sql_select = "SELECT file_materi FROM modul WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $modul_id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$modul = $result->fetch_assoc();

if ($modul && !empty($modul['file_materi'])) {
    $file_path = '../uploads/materi/' . $modul['file_materi'];
    if (file_exists($file_path)) {
        unlink($file_path); // Hapus file fisik
    }
}
$stmt_select->close();

// 2. Hapus record dari database
$sql_delete = "DELETE FROM modul WHERE id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $modul_id);

if ($stmt_delete->execute()) {
    header("Location: $redirect_url&status=sukses&pesan=Modul berhasil dihapus.");
} else {
    header("Location: $redirect_url&status=gagal&pesan=Gagal menghapus modul.");
}
$stmt_delete->close();
$conn->close();
?>