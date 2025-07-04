<?php
require_once '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

// Ambil data dari form
$praktikum_id = $_POST['praktikum_id'];
$modul_id = $_POST['modul_id'];
$mahasiswa_id = $_SESSION['user_id'];
$redirect_url = "detail_praktikum.php?id=$praktikum_id";

// Cek apakah file diunggah
if (isset($_FILES["file_laporan"]) && $_FILES["file_laporan"]["error"] == 0) {
    $allowed_types = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
    $file_type = $_FILES["file_laporan"]["type"];

    if (!in_array($file_type, $allowed_types)) {
        header("Location: $redirect_url&upload_status=gagal&error=Tipe file tidak diizinkan. Hanya PDF, DOC, DOCX.");
        exit;
    }

    // Buat nama file unik untuk mencegah tumpang tindih
    $file_extension = pathinfo($_FILES["file_laporan"]["name"], PATHINFO_EXTENSION);
    $new_filename = "laporan_" . $modul_id . "_" . $mahasiswa_id . "_" . time() . "." . $file_extension;
    $upload_dir = "../uploads/laporan/";
    $upload_file = $upload_dir . $new_filename;

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($_FILES["file_laporan"]["tmp_name"], $upload_file)) {
        // Jika berhasil, simpan path file ke database
        $sql_insert = "INSERT INTO laporan (modul_id, mahasiswa_id, file_laporan) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iis", $modul_id, $mahasiswa_id, $new_filename);
        
        if ($stmt_insert->execute()) {
            header("Location: $redirect_url&upload_status=sukses");
        } else {
            // Jika gagal insert DB, hapus file yang sudah diupload
            unlink($upload_file);
            header("Location: $redirect_url&upload_status=gagal&error=Gagal menyimpan data ke database.");
        }
        $stmt_insert->close();
    } else {
        header("Location: $redirect_url&upload_status=gagal&error=Gagal mengunggah file.");
    }
} else {
    header("Location: $redirect_url&upload_status=gagal&error=Tidak ada file yang diunggah atau terjadi kesalahan.");
}

$conn->close();
exit;