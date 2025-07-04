<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$modul_id = $_POST['modul_id'];
$praktikum_id = $_POST['praktikum_id'];
$nama_modul = $_POST['nama_modul'];
$deskripsi_modul = $_POST['deskripsi_modul'];
$file_materi_lama = $_POST['file_materi_lama'];
$nama_file_materi = $file_materi_lama;

$redirect_url = "kelola_modul.php?praktikum_id=$praktikum_id";

// Cek apakah ada file baru yang diupload
if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
    $upload_dir = '../uploads/materi/';
    // Hapus file lama jika ada
    if (!empty($file_materi_lama) && file_exists($upload_dir . $file_materi_lama)) {
        unlink($upload_dir . $file_materi_lama);
    }
    // Upload file baru
    $nama_file_materi = time() . '_' . basename($_FILES['file_materi']['name']);
    $target_file = $upload_dir . $nama_file_materi;
    if (!move_uploaded_file($_FILES['file_materi']['tmp_name'], $target_file)) {
        header("Location: $redirect_url&status=gagal&pesan=Gagal mengunggah file baru.");
        exit;
    }
}

$sql = "UPDATE modul SET nama_modul = ?, deskripsi_modul = ?, file_materi = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nama_modul, $deskripsi_modul, $nama_file_materi, $modul_id);

if ($stmt->execute()) {
    header("Location: $redirect_url&status=sukses&pesan=Modul berhasil diupdate.");
} else {
    header("Location: $redirect_url&status=gagal&pesan=Gagal mengupdate data.");
}
$stmt->close();
$conn->close();
?>