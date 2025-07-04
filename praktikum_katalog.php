<?php
require_once 'config.php';
session_start(); // Diperlukan untuk mengecek status login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Mata Praktikum - SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Katalog Mata Praktikum</h1>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $_SESSION['role'] === 'mahasiswa' ? 'mahasiswa/dashboard.php' : 'asisten/dashboard.php'; ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Kembali ke Dashboard</a>
                <?php else: ?>
                    <a href="login.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'sukses'): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">Anda berhasil mendaftar praktikum.</span>
                </div>
            <?php elseif ($_GET['status'] === 'gagal'): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $sql = "SELECT id, kode_praktikum, nama_praktikum, deskripsi FROM mata_praktikum ORDER BY nama_praktikum ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">(<?php echo htmlspecialchars($row['kode_praktikum']); ?>) <?php echo htmlspecialchars($row['nama_praktikum']); ?></h2>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'mahasiswa'): ?>
                    <a href="proses_daftar.php?id=<?php echo $row['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full text-center inline-block">Daftar Praktikum</a>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Login sebagai mahasiswa untuk mendaftar.</p>
                <?php endif; ?>
            </div>
            <?php
                endwhile;
            else:
                echo "<p class='col-span-full text-center'>Belum ada mata praktikum yang tersedia.</p>";
            endif;
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>