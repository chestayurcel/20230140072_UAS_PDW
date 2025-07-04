<?php
require_once '../config.php';
session_start();

if ($_SESSION['role'] !== 'asisten') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM mata_praktikum WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: kelola_praktikum.php?status=sukses&pesan=Data berhasil dihapus");
    } else {
        header("Location: kelola_praktikum.php?status=gagal&pesan=Gagal menghapus data");
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: kelola_praktikum.php");
    exit();
}
?>