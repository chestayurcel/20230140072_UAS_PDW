<?php
require_once 'templates/header.php';
require_once '../config.php';

// Pastikan ada ID praktikum yang dikirim lewat URL
if (!isset($_GET['praktikum_id']) || !is_numeric($_GET['praktikum_id'])) {
    header("Location: kelola_praktikum.php?status=gagal&pesan=ID Praktikum tidak valid.");
    exit;
}

$praktikum_id = $_GET['praktikum_id'];

// Ambil nama praktikum untuk judul halaman
$sql_praktikum = "SELECT nama_praktikum FROM mata_praktikum WHERE id = ?";
$stmt_praktikum = $conn->prepare($sql_praktikum);
$stmt_praktikum->bind_param("i", $praktikum_id);
$stmt_praktikum->execute();
$result_praktikum = $stmt_praktikum->get_result();
$praktikum = $result_praktikum->fetch_assoc();
$stmt_praktikum->close();
?>

<div class="container mx-auto">
    <a href="kelola_praktikum.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Praktikum</a>
    <h2 class="text-2xl font-bold mb-4">Kelola Modul untuk: <?php echo htmlspecialchars($praktikum['nama_praktikum']); ?></h2>

    <?php if (isset($_GET['status'])): ?>
        <div class="mb-4 p-4 rounded text-white <?php echo $_GET['status'] == 'sukses' ? 'bg-green-500' : 'bg-red-500'; ?>">
            <?php echo htmlspecialchars($_GET['pesan']); ?>
        </div>
    <?php endif; ?>

    <a href="tambah_modul.php?praktikum_id=<?php echo $praktikum_id; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
        + Tambah Modul Baru
    </a>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Modul</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Materi</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nama_modul, file_materi FROM modul WHERE praktikum_id = ? ORDER BY id ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $praktikum_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['nama_modul']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php if(!empty($row['file_materi'])): ?>
                            <a href="../uploads/materi/<?php echo htmlspecialchars($row['file_materi']); ?>" target="_blank" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($row['file_materi']); ?></a>
                        <?php else: ?>
                            <span class="text-gray-500 italic">Tidak ada file</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="edit_modul.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a href="hapus_modul.php?id=<?php echo $row['id']; ?>&praktikum_id=<?php echo $praktikum_id; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Anda yakin ingin menghapus modul ini?');">Hapus</a>
                    </td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="3" class="text-center py-5">Belum ada modul untuk praktikum ini.</td>
                </tr>
                <?php endif; $stmt->close(); $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>