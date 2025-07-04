<?php
require_once 'templates/header.php';
require_once '../config.php';

$user_id = $_GET['id'];
// Mencegah edit diri sendiri dari halaman ini
if ($user_id == $_SESSION['user_id']) {
    header("Location: kelola_pengguna.php?status=gagal&pesan=Anda tidak dapat mengedit akun Anda sendiri dari sini.");
    exit;
}

$sql = "SELECT nama, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<div class="container mx-auto">
    <a href="kelola_pengguna.php" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Pengguna</a>
    <h2 class="text-2xl font-bold mb-4">Edit Pengguna</h2>
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md mx-auto">
        <form action="proses_edit_pengguna.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
            <div class="mb-4">
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap:</label>
                <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password Baru:</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <p class="text-xs text-gray-600 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
            <div class="mb-6">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="mahasiswa" <?php echo ($user['role'] == 'mahasiswa') ? 'selected' : ''; ?>>Mahasiswa</option>
                    <option value="asisten" <?php echo ($user['role'] == 'asisten') ? 'selected' : ''; ?>>Asisten</option>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Pengguna</button>
            </div>
        </form>
    </div>
</div>

<?php $conn->close(); require_once 'templates/footer.php'; ?>