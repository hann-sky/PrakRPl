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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    // Save the image to the wisata folder
    $imagePath = 'wisata/' . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        die("Failed to upload image.");
    }

    // Insert the data into the database
    $stmt = $koneksi->prepare("INSERT INTO destinasi (title, description, images) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $koneksi->error);
    }

    $stmt->bind_param("sss", $title, $description, $imagePath);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    echo "Destinasi berhasil disimpan.";

    $stmt->close();
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Destinasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-button a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            text-align: center;
            background-color: #6c757d;
            border-radius: 5px;
            margin-top: 10px;
        }

        .back-button a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2>Tambah Destinasi Baru</h2>
        </header>
        <main>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Judul:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Gambar:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit">Simpan</button>
            </form>
            <div class="back-button"><a href="akunAdmin.php">Kembali</a></div>
        </main>
    </div>
</body>
</html>
