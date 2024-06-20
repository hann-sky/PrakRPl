<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "root";
$database = "wisata";

// Membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fetch the list of destinations from the database
$sql = "SELECT title, description, images FROM destinasi";
$result = $koneksi->query($sql); // Menggunakan $koneksi, bukan $conn

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Destinasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        header h2 {
            text-align: center;
            color: #333;
        }
        .destination {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .destination img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
        .destination h3 {
            margin: 0;
            color: #007bff;
        }
        .destination p {
            color: #333;
        }
        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .back-button a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2>Daftar Destinasi</h2>
        </header>
        <main>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="destination">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<img src="' . htmlspecialchars($row['images']) . '" alt="Gambar Destinasi">'; // Ganti dari $row['image'] menjadi $row['images']
                    echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Tidak ada destinasi yang ditemukan.</p>';
            }
            $koneksi->close(); // Menggunakan $koneksi untuk menutup koneksi
            ?>
            <div class="back-button">
                <a href="inputDestinasi.php">Tambah Destinasi Baru</a>
            </div>
        </main>
    </div>
</body>
</html>
