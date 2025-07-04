<?php
require_once 'templates/header.php';

// Ambil praktikum_id dari URL untuk disertakan dalam form
$praktikum_id = $_GET['praktikum_id'];
?>

<div class="container mx-auto">
    <a href="kelola_modul.php?praktikum_id=<?php echo $praktikum_id; ?>" class="text-blue-500 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Modul</a>
    <h2 class="text-2xl font-bold mb-4">Tambah Modul Baru</h2>
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="proses_tambah_modul.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="praktikum_id" value="<?php echo $praktikum_id; ?>">
            <div class="mb-4">
                <label for="nama_modul" class="block text-gray-700 text-sm font-bold mb-2">Nama Modul:</label>
                <input type="text" name="nama_modul" id="nama_modul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="deskripsi_modul" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Singkat:</label>
                <textarea name="deskripsi_modul" id="deskripsi_modul" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-6">
                <label for="file_materi" class="block text-gray-700 text-sm font-bold mb-2">File Materi (PDF/DOCX):</label>
                <input type="file" name="file_materi" id="file_materi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan Modul</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>