<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $praktikum_id = $_POST['praktikum_id'];
    $nama_modul = $_POST['nama_modul'];
    $deskripsi_modul = $_POST['deskripsi_modul'];
    $nama_file_materi = null;

    $redirect_url = "kelola_modul.php?praktikum_id=$praktikum_id";

    // Logika untuk upload file
    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
        $upload_dir = '../uploads/materi/';
        // Buat nama file unik untuk menghindari konflik
        $nama_file_materi = time() . '_' . basename($_FILES['file_materi']['name']);
        $target_file = $upload_dir . $nama_file_materi;

        // Pindahkan file ke folder uploads/materi
        if (!move_uploaded_file($_FILES['file_materi']['tmp_name'], $target_file)) {
            header("Location: $redirect_url&status=gagal&pesan=Gagal mengunggah file.");
            exit;
        }
    }

    $sql = "INSERT INTO modul (praktikum_id, nama_modul, deskripsi_modul, file_materi) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $praktikum_id, $nama_modul, $deskripsi_modul, $nama_file_materi);

    if ($stmt->execute()) {
        header("Location: $redirect_url&status=sukses&pesan=Modul berhasil ditambahkan.");
    } else {
        header("Location: $redirect_url&status=gagal&pesan=Gagal menyimpan data ke database.");
    }
    $stmt->close();
    $conn->close();
}
?>