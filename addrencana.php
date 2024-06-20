<?php
session_start();
include 'koneksi.php';

$error = "";
$success = "";
$event = [
    'nama' => '',
    'deskripsi' => '',
    'gambar' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaEvent = isset($_POST['nama']) ? $_POST['nama'] : '';
    $deskripsi = $_POST['deskripsi'];
    $gambar = $_FILES['gambar']['name'];
    
    if (empty($namaEvent) || empty($deskripsi) || empty($tanggalMulai) || empty($lokasiEvent)) {
        $error = "Semua field wajib diisi!";
    } else {
        if ($gambar) {
            // Proses upload gambar
            $targetDir = "wisata/";
            $targetFile = $targetDir . basename($gambar);
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $targetFile = isset($_POST['existing_image']) ? $_POST['existing_image'] : '';
        }

        if (!$error) {
            // Cek apakah event sudah ada atau baru
            $existingEventQuery = "SELECT * FROM rencana WHERE nama='$namaEvent'";
            $existingEventResult = mysqli_query($koneksi, $existingEventQuery);

            if ($existingEventResult && mysqli_num_rows($existingEventResult) > 0) {
                // Update event
                $sql = "UPDATE rencana SET deskripsi='$deskripsi', gambar='$targetFile' WHERE nama='$namaEvent'";
                $success = "Event berhasil diperbarui!";
            } else {
                // Insert new event
                $sql = "INSERT INTO rencana (nama, deskripsi, gambar) VALUES ('$namaEvent', '$deskripsi', '$targetFile')";
                $success = "Event berhasil ditambahkan!";
            }

            if (!mysqli_query($koneksi, $sql)) {
                $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
    }
} elseif (isset($_GET['nama'])) {
    // Mengambil data untuk edit
    $namaEvent = $_GET['nama'];
    $result = $koneksi->query("SELECT * FROM rencana WHERE nama='$namaEvent'");
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        $error = "Event tidak ditemukan.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_GET['nama']) ? 'Edit Event' : 'Tambah Rencana'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="addev.css">
    <style>
        
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2><?php echo isset($_GET['nama']) ? 'Edit Event' : 'Tambah Event'; ?></h2>
        <?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
        <?php if ($success) echo '<p class="success">'.htmlspecialchars($success).'</p>'; ?>
        <form id="event-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nama" value="<?php echo htmlspecialchars($event['nama']); ?>">
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="tampilkanGambar()">
                <?php if (isset($event['gambar']) && $event['gambar']) {
                    echo '<input type="hidden" name="existing_image" value="' . htmlspecialchars($event['gambar']) . '">';
                    echo '<img id="gambarPreview" src="' . htmlspecialchars($event['gambar']) . '" alt="Gambar Preview" style="max-width: 100%; height: auto;">';
                } else {
                    echo '<img id="gambarPreview" src="" alt="Gambar Preview" style="display: none; max-width: 100%; height: auto;">';
                } ?>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($event['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($event['deskripsi']); ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
        <button type="click" name="submit" class="btn btn-primary"><a href="Event.php">Kembali</a></button>

    </div>
    <script>
        function tampilkanGambar() {
            var fileInput = document.getElementById('gambar');
            var preview = document.getElementById('gambarPreview');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
