<?php
require_once 'templates/header.php';
require_once '../config.php';
?>

<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Kelola Mata Praktikum</h2>

    <?php if (isset($_GET['status'])): ?>
        <div class="mb-4 p-4 rounded text-white <?php echo $_GET['status'] == 'sukses' ? 'bg-green-500' : 'bg-red-500'; ?>">
            <?php echo htmlspecialchars($_GET['pesan']); ?>
        </div>
    <?php endif; ?>

    <a href="tambah_praktikum.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
        + Tambah Mata Praktikum
    </a>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Praktikum</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, kode_praktikum, nama_praktikum FROM mata_praktikum ORDER BY kode_praktikum";
                $result = $conn->query($sql);
                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['kode_praktikum']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['nama_praktikum']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="kelola_modul.php?praktikum_id=<?php echo $row['id']; ?>" class="text-green-600 hover:text-green-900 mr-3">Kelola Modul</a>
                        <a href="edit_praktikum.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a href="hapus_praktikum.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Anda yakin ingin menghapus praktikum ini? Semua modul dan laporan terkait akan ikut terhapus!');">Hapus</a>
                    </td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="3" class="text-center py-5">Tidak ada data mata praktikum.</td>
                </tr>
                <?php endif; $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>