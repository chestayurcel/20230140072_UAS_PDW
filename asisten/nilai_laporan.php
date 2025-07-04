<?php
require_once 'templates/header.php';
require_once '../config.php';

if (!isset($_GET['laporan_id'])) {
    header("Location: kelola_laporan.php");
    exit;
}
$laporan_id = $_GET['laporan_id'];

$sql = "SELECT l.*, u.nama AS nama_mahasiswa, mp.nama_praktikum, m.nama_modul 
        FROM laporan l
        JOIN users u ON l.mahasiswa_id = u.id
        JOIN modul m ON l.modul_id = m.id
        JOIN mata_praktikum mp ON m.praktikum_id = mp.id
        WHERE l.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $laporan_id);
$stmt->execute();
$result = $stmt->get_result();
$laporan = $result->fetch_assoc();

if (!$laporan) {
    echo "Laporan tidak ditemukan.";
    exit;
}
?>

<div class="container mx-auto">
    <a href="kelola_laporan.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Laporan</a>
    <h2 class="text-2xl font-bold mb-4">Beri Nilai Laporan</h2>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold mb-2">Detail Laporan</h3>
        <p><strong>Mahasiswa:</strong> <?php echo htmlspecialchars($laporan['nama_mahasiswa']); ?></p>
        <p><strong>Praktikum:</strong> <?php echo htmlspecialchars($laporan['nama_praktikum']); ?></p>
        <p><strong>Modul:</strong> <?php echo htmlspecialchars($laporan['nama_modul']); ?></p>
        <p><strong>File Laporan:</strong> <a href="../uploads/laporan/<?php echo htmlspecialchars($laporan['file_laporan']); ?>" download class="text-blue-500 hover:underline"><?php echo htmlspecialchars($laporan['file_laporan']); ?></a></p>
    </div>

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="proses_nilai.php" method="POST">
            <input type="hidden" name="laporan_id" value="<?php echo $laporan['id']; ?>">
            <div class="mb-4">
                <label for="nilai" class="block text-gray-700 text-sm font-bold mb-2">Nilai (0-100):</label>
                <input type="number" name="nilai" id="nilai" min="0" max="100" value="<?php echo htmlspecialchars($laporan['nilai']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="feedback" class="block text-gray-700 text-sm font-bold mb-2">Feedback (Opsional):</label>
                <textarea name="feedback" id="feedback" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($laporan['feedback']); ?></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>

<?php $stmt->close(); $conn->close(); require_once 'templates/footer.php'; ?>