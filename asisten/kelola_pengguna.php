<?php
require_once 'templates/header.php';
require_once '../config.php';
?>

<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Kelola Akun Pengguna</h2>
    
    <a href="tambah_pengguna.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
        + Tambah Pengguna Baru
    </a>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nama, email, role FROM users ORDER BY nama";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><span class="capitalize"><?php echo htmlspecialchars($row['role']); ?></span></td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <?php if ($row['id'] != $_SESSION['user_id']): // Mencegah user menghapus/mengedit diri sendiri ?>
                            <a href="edit_pengguna.php?id=<?php echo $row['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="hapus_pengguna.php?id=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                        <?php else: ?>
                            <span class="text-gray-500 italic">Akun Anda</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; $conn->close(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>