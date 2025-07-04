<?php
require_once 'templates/header.php';
?>

<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Tambah Mata Praktikum Baru</h2>
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="proses_tambah_praktikum.php" method="POST">
            <div class="mb-4">
                <label for="kode_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Kode Praktikum:</label>
                <input type="text" name="kode_praktikum" id="kode_praktikum" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="nama_praktikum" class="block text-gray-700 text-sm font-bold mb-2">Nama Praktikum:</label>
                <input type="text" name="nama_praktikum" id="nama_praktikum" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan</button>
                <a href="kelola_praktikum.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>