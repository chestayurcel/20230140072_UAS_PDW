<?php
require_once 'templates/header.php';
require_once '../config.php';

$modul_id = $_GET['id'];
$sql = "SELECT * FROM modul WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $modul_id);
$stmt->execute();
$result = $stmt->get_result();
$modul = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<div class="container mx-auto">
    <a href="kelola_modul.php?praktikum_id=<?php echo $modul['praktikum_id']; ?>" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Modul</a>
    <h2 class="text-2xl font-bold mb-4">Edit Modul</h2>
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="proses_edit_modul.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="modul_id" value="<?php echo $modul['id']; ?>">
            <input type="hidden" name="praktikum_id" value="<?php echo $modul['praktikum_id']; ?>">
            <input type="hidden" name="file_materi_lama" value="<?php echo $modul['file_materi']; ?>">
            
            <div class="mb-4">
                <label for="nama_modul" class="block text-gray-700 text-sm font-bold mb-2">Nama Modul:</label>
                <input type="text" name="nama_modul" id="nama_modul" value="<?php echo htmlspecialchars($modul['nama_modul']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi_modul" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Singkat:</label>
                <textarea name="deskripsi_modul" id="deskripsi_modul" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?php echo htmlspecialchars($modul['deskripsi_modul']); ?></textarea>
            </div>
            <div class="mb-6">
                <label for="file_materi" class="block text-gray-700 text-sm font-bold mb-2">Ganti File Materi (Opsional):</label>
                <p class="text-sm text-gray-600 mb-2">File saat ini: <?php echo !empty($modul['file_materi']) ? htmlspecialchars($modul['file_materi']) : 'Tidak ada'; ?></p>
                <input type="file" name="file_materi" id="file_materi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Modul</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>