<?php
session_start();
include 'koneksi.php';

$error = "";
$success = "";
$event = [
    'nama_wisata' => '',
    'detail_wisata' => '',
    'tanggal_mulai' => '',
    'tanggal_akhir' => '',
    'lokasi' => '',
    'hargaTiket' => '',
    'gambar' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaEvent = isset($_POST['nama_wisata']) ? $_POST['nama_wisata'] : '';
    $deskripsi = $_POST['detail_wisata'];
    $tanggalMulai = $_POST['tanggal_mulai'];
    $tanggalAkhir = $_POST['tanggal_akhir'];
    $lokasiEvent = $_POST['lokasi'];
    $hargaTiket = isset($_POST['hargaTiket']) ? $_POST['hargaTiket'] : '';
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
            $existingEventQuery = "SELECT * FROM wisata WHERE nama_wisata='$namaEvent'";
            $existingEventResult = mysqli_query($koneksi, $existingEventQuery);

            if ($existingEventResult && mysqli_num_rows($existingEventResult) > 0) {
                // Update event
                $sql = "UPDATE wisata SET detail_wisata='$deskripsi', tanggal_mulai='$tanggalMulai', tanggal_akhir='$tanggalAkhir', lokasi='$lokasiEvent', hargaTiket='$hargaTiket', gambar='$targetFile' WHERE nama_wisata='$namaEvent'";
                $success = "Event berhasil diperbarui!";
            } else {
                // Insert new event
                $sql = "INSERT INTO wisata (nama_wisata, detail_wisata, tanggal_mulai, tanggal_akhir, lokasi, hargaTiket, gambar) VALUES ('$namaEvent', '$deskripsi', '$tanggalMulai', '$tanggalAkhir', '$lokasiEvent', '$hargaTiket', '$targetFile')";
                $success = "Event berhasil ditambahkan!";
            }

            if (!mysqli_query($koneksi, $sql)) {
                $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
    }
} elseif (isset($_GET['nama_wisata'])) {
    // Mengambil data untuk edit
    $namaEvent = $_GET['nama_wisata'];
    $result = $koneksi->query("SELECT * FROM wisata WHERE nama_wisata='$namaEvent'");
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
    <title><?php echo isset($_GET['nama_wisata']) ? 'Edit Event' : 'Tambah Event'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="addev.css">
    <style>
        
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2><?php echo isset($_GET['nama_wisata']) ? 'Edit Event' : 'Tambah Event'; ?></h2>
        <?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
        <?php if ($success) echo '<p class="success">'.htmlspecialchars($success).'</p>'; ?>
        <form id="event-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nama_wisata" value="<?php echo htmlspecialchars($event['nama_wisata']); ?>">
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
                <label for="nama_wisata" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" value="<?php echo htmlspecialchars($event['nama_wisata']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="detail_wisata" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="detail_wisata" name="detail_wisata" rows="3" required><?php echo htmlspecialchars($event['detail_wisata']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo htmlspecialchars($event['tanggal_mulai']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo htmlspecialchars($event['tanggal_akhir']); ?>">
            </div>
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi Event</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo htmlspecialchars($event['lokasi']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="hargaTiket" class="form-label">Harga Tiket (jika ada)</label>
                <input type="text" class="form-control" id="hargaTiket" name="hargaTiket" value="<?php echo htmlspecialchars($event['hargaTiket']); ?>">
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
