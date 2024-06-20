<?php
session_start();
include 'koneksi.php';

// Check if connection is successful
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM inspirasi";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
    <link rel="stylesheet" href="evv.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="akunAdmin.php">Admin - A</a></li>
                    <li><a href="event.php">Event</a></li>
                    <li><a href="inputDestinasi.php">Destinasi</a></li>
                    <li><a href="inspirasi.php">Inspirasi Perjalanan</a></li>
                    <li><a href="rencana.php">Rencana Perjalanan</a></li>
                    <li><a href="logout.php">Keluar Akun</a></li>
                </ul>
            </nav>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Event</h1>
                <button onclick="window.location.href='addinspirasi.php'" class="add-event-button">Tambah Inspirasi</button>
            </div>
            <div class="event-list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="event-card">
                        <img src="<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>">
                        <div class="event-info">
                            <h3><?php echo $row['nama']; ?></h3>
                            <p><?php echo $row['deskripsi']; ?></p>
                        </div>
                        <button onclick="window.location.href='addinspirasi.php?nama_wisata=<?php echo $row['nama']; ?>'" class="edit-button">Edit</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>PT WISATA INDONESIA | Contact: | Email:</p>
    </div>
</body>
</html>
