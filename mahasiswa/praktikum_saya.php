<?php
require_once 'templates/header_mahasiswa.php';
require_once '../config.php';
?>

<h2 class="text-2xl font-bold mb-4">Daftar Praktikum yang Anda Ikuti</h2>
<div class="bg-white shadow-md rounded-lg overflow-hidden">
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
            $mahasiswa_id = $_SESSION['user_id'];
            $sql = "SELECT mp.id, mp.kode_praktikum, mp.nama_praktikum 
                    FROM mata_praktikum mp
                    JOIN pendaftaran_praktikum pp ON mp.id = pp.praktikum_id
                    WHERE pp.mahasiswa_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $mahasiswa_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['kode_praktikum']); ?></td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['nama_praktikum']); ?></td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <a href="detail_praktikum.php?id=<?php echo $row['id']; ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                        Lihat Detail & Tugas
                    </a>
                </td>
            </tr>
            <?php 
                endwhile;
            else: ?>
                <tr><td colspan="3" class="text-center py-5">Anda belum mendaftar praktikum apapun. Silakan mendaftar melalui <a href="../praktikum_katalog.php" class="text-blue-500 hover:underline">katalog</a>.</td></tr>
            <?php 
            endif;
            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<?php
require_once 'templates/footer_mahasiswa.php';
?>