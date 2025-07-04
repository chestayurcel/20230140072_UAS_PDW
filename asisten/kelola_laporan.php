<?php
require_once 'templates/header.php';
require_once '../config.php';

// Logic untuk filter
$where_clauses = [];
$params = [];
$types = '';

if (!empty($_GET['praktikum'])) {
    $where_clauses[] = "mp.id = ?";
    $params[] = $_GET['praktikum'];
    $types .= 'i';
}
if (!empty($_GET['mahasiswa'])) {
    $where_clauses[] = "u.id = ?";
    $params[] = $_GET['mahasiswa'];
    $types .= 'i';
}
if (!empty($_GET['status'])) {
    $where_clauses[] = "l.status = ?";
    $params[] = $_GET['status'];
    $types .= 's';
}

$sql_laporan = "SELECT l.id, u.nama AS nama_mahasiswa, mp.nama_praktikum, m.nama_modul, l.tanggal_kumpul, l.status 
                FROM laporan l
                JOIN users u ON l.mahasiswa_id = u.id
                JOIN modul m ON l.modul_id = m.id
                JOIN mata_praktikum mp ON m.praktikum_id = mp.id";

if (!empty($where_clauses)) {
    $sql_laporan .= " WHERE " . implode(" AND ", $where_clauses);
}
$sql_laporan .= " ORDER BY l.tanggal_kumpul DESC";

$stmt_laporan = $conn->prepare($sql_laporan);
if (!empty($params)) {
    $stmt_laporan->bind_param($types, ...$params);
}
$stmt_laporan->execute();
$result_laporan = $stmt_laporan->get_result();

?>

<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Laporan Masuk</h2>

    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form action="kelola_laporan.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="praktikum" class="block text-sm font-medium text-gray-700">Praktikum</label>
                <select name="praktikum" id="praktikum" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua</option>
                    <?php
                    $result_praktikum = $conn->query("SELECT id, nama_praktikum FROM mata_praktikum ORDER BY nama_praktikum");
                    while($row = $result_praktikum->fetch_assoc()) {
                        $selected = (isset($_GET['praktikum']) && $_GET['praktikum'] == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$selected}>" . htmlspecialchars($row['nama_praktikum']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="mahasiswa" class="block text-sm font-medium text-gray-700">Mahasiswa</label>
                <select name="mahasiswa" id="mahasiswa" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua</option>
                    <?php
                    $result_mahasiswa = $conn->query("SELECT id, nama FROM users WHERE role = 'mahasiswa' ORDER BY nama");
                    while($row = $result_mahasiswa->fetch_assoc()) {
                        $selected = (isset($_GET['mahasiswa']) && $_GET['mahasiswa'] == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$selected}>" . htmlspecialchars($row['nama']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Semua</option>
                    <option value="dikumpulkan" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dikumpulkan') ? 'selected' : ''; ?>>Dikumpulkan</option>
                    <option value="dinilai" <?php echo (isset($_GET['status']) && $_GET['status'] == 'dinilai') ? 'selected' : ''; ?>>Dinilai</option>
                </select>
            </div>
            <div class="self-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                <a href="kelola_laporan.php" class="ml-2 text-gray-600 hover:text-gray-900">Reset</a>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['status_nilai'])): ?>
        <div class="mb-4 p-4 rounded text-white <?php echo $_GET['status_nilai'] == 'sukses' ? 'bg-green-500' : 'bg-red-500'; ?>">
            <?php echo htmlspecialchars($_GET['pesan']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mahasiswa</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Praktikum & Modul</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Kumpul</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_laporan->num_rows > 0): while($row = $result_laporan->fetch_assoc()): ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="font-bold"><?php echo htmlspecialchars($row['nama_praktikum']); ?></p>
                        <p class="text-gray-600"><?php echo htmlspecialchars($row['nama_modul']); ?></p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo date('d M Y, H:i', strtotime($row['tanggal_kumpul'])); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="capitalize px-2 py-1 rounded-full text-xs font-semibold <?php echo $row['status'] === 'dinilai' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800'; ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="nilai_laporan.php?laporan_id=<?php echo $row['id']; ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs">Lihat & Nilai</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center py-5">Tidak ada data laporan yang cocok dengan filter.</td></tr>
                <?php endif; $stmt_laporan->close(); $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>