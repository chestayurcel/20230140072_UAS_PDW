<?php
require_once 'templates/header_mahasiswa.php';
require_once '../config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>ID Praktikum tidak valid.</p>";
    require_once 'templates/footer.php';
    exit;
}
$praktikum_id = $_GET['id'];
$mahasiswa_id = $_SESSION['user_id'];

// Ambil info detail praktikum
$sql_praktikum = "SELECT nama_praktikum FROM mata_praktikum WHERE id = ?";
$stmt_praktikum = $conn->prepare($sql_praktikum);
$stmt_praktikum->bind_param("i", $praktikum_id);
$stmt_praktikum->execute();
$result_praktikum = $stmt_praktikum->get_result();
$praktikum = $result_praktikum->fetch_assoc();
$stmt_praktikum->close();
?>

<h2 class="text-2xl font-bold mb-4">Detail Praktikum: <?php echo htmlspecialchars($praktikum['nama_praktikum']); ?></h2>

<?php if (isset($_GET['upload_status'])): ?>
    <?php if ($_GET['upload_status'] === 'sukses'): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong> Laporan Anda berhasil diunggah.
        </div>
    <?php else: ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="space-y-6">
    <?php
    // Ambil semua modul untuk praktikum ini
    $sql_modul = "SELECT id, nama_modul, deskripsi_modul, file_materi FROM modul WHERE praktikum_id = ? ORDER BY id ASC";
    $stmt_modul = $conn->prepare($sql_modul);
    $stmt_modul->bind_param("i", $praktikum_id);
    $stmt_modul->execute();
    $result_modul = $stmt_modul->get_result();
    
    if ($result_modul->num_rows > 0) {
        while ($modul = $result_modul->fetch_assoc()) {
            $modul_id = $modul['id'];

            // Untuk setiap modul, cek status laporan mahasiswa ini
            $sql_laporan = "SELECT file_laporan, nilai, feedback, status FROM laporan WHERE modul_id = ? AND mahasiswa_id = ?";
            $stmt_laporan = $conn->prepare($sql_laporan);
            $stmt_laporan->bind_param("ii", $modul_id, $mahasiswa_id);
            $stmt_laporan->execute();
            $result_laporan = $stmt_laporan->get_result();
            $laporan = $result_laporan->fetch_assoc();
            $stmt_laporan->close();
    ?>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($modul['nama_modul']); ?></h3>
        <p class="text-gray-600 mt-1 mb-4"><?php echo htmlspecialchars($modul['deskripsi_modul']); ?></p>

        <?php if (!empty($modul['file_materi'])): ?>
            <a href="../uploads/materi/<?php echo htmlspecialchars($modul['file_materi']); ?>" download class="text-blue-500 hover:underline">
                Unduh Materi (<?php echo htmlspecialchars($modul['file_materi']); ?>)
            </a>
        <?php else: ?>
            <p class="text-gray-500 italic">Materi belum tersedia.</p>
        <?php endif; ?>

        <hr class="my-4">

        <h4 class="font-semibold mb-2">Laporan Anda:</h4>
        <?php if ($laporan): // Jika laporan sudah ada ?>
            <p><strong>Status:</strong> <span class="capitalize font-medium <?php echo $laporan['status'] === 'dinilai' ? 'text-green-600' : 'text-yellow-600'; ?>"><?php echo htmlspecialchars($laporan['status']); ?></span></p>
            <p><strong>File Terkumpul:</strong> <a href="../uploads/laporan/<?php echo htmlspecialchars($laporan['file_laporan']); ?>" download class="text-blue-500 hover:underline"><?php echo htmlspecialchars($laporan['file_laporan']); ?></a></p>
            
            <?php if ($laporan['status'] === 'dinilai'): ?>
                <div class="mt-2 p-3 bg-green-50 rounded-lg border border-green-200">
                    <p><strong>Nilai:</strong> <span class="text-2xl font-bold text-green-700"><?php echo htmlspecialchars($laporan['nilai']); ?></span></p>
                    <p class="mt-1"><strong>Feedback Asisten:</strong> <?php echo !empty($laporan['feedback']) ? htmlspecialchars($laporan['feedback']) : 'Tidak ada feedback.'; ?></p>
                </div>
            <?php endif; ?>

        <?php else: // Jika belum ada laporan, tampilkan form upload ?>
            <form action="proses_upload.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="praktikum_id" value="<?php echo $praktikum_id; ?>">
                <input type="hidden" name="modul_id" value="<?php echo $modul_id; ?>">
                <div>
                    <label for="file_laporan_<?php echo $modul_id; ?>" class="block text-sm font-medium text-gray-700">Unggah File Laporan (PDF, DOC, DOCX):</label>
                    <input type="file" name="file_laporan" id="file_laporan_<?php echo $modul_id; ?>" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                </div>
                <button type="submit" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Kumpulkan Laporan</button>
            </form>
        <?php endif; ?>
    </div>
    <?php
        }
    } else {
        echo "<p>Belum ada modul yang ditambahkan untuk praktikum ini.</p>";
    }
    $stmt_modul->close();
    $conn->close();
    ?>
</div>

<?php
require_once 'templates/footer_mahasiswa.php';
?>