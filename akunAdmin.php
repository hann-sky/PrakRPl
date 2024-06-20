<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$error = "";
$sukses = "";

$username = $_SESSION['username'];

$sql = "SELECT nama_lengkap, username, gender, tanggal_lahir, alamat, email, phone, gambar FROM person WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($nama_lengkap, $username, $gender, $tanggal_lahir, $alamat, $email, $phone, $gambar);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "ppuser/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);

    if ($username && $nama_lengkap && $gender && $tanggal_lahir && $alamat && $email && $phone) {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql1 = "UPDATE person SET username=?, nama_lengkap=?, gender=?, tanggal_lahir=?, alamat=?, email=?, phone=?, gambar=? WHERE username=?";
            $stmt = $koneksi->prepare($sql1);
            $stmt->bind_param("sssssssss", $username, $nama_lengkap, $gender, $tanggal_lahir, $alamat, $email, $phone, $target_file, $_SESSION['username']);
            if ($stmt->execute()) {
                $sukses = "Data berhasil diupdate";
                $_SESSION['username'] = $username;
            } else {
                $error = "Data gagal diupdate";
            }
            $stmt->close();
        } else {
            $error = "Gagal mengupload gambar";
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <header>
        <img src="asset/LOGOTRAV.jpg" alt="Logo" id="logo">
        <h6 id="dashboard"><a href="dashboard.php">Dashboard</a></h6>
    </header>
    <div class="container">
        <div class="sidebar">
            <div class="profile" id="vertikel">
                <img src="<?php echo htmlspecialchars($gambar); ?>" alt="Profile Picture">
                <h2><?php echo htmlspecialchars($username); ?></h2>
            </div>
            <nav>
                <ul>
                    <li><a href="akunAdmin.php">Admin - A</a></li>
                    <li><a href="event.php">Event</a></li>
                    <li><a href="inputDestinasi.php">Destinasi</a></li>
                    <li><a href="inspirasi.php">Inspirasi Perjalanan</a></li>
                    <li><a href="rencana.php">Rencana Perjalanan</a></li>
                    <li>
                        <form method="post">
                            <button type="submit" name="logout" class="red-button" onclick="return confirm('Are you sure you want to logout?');"><a href="das.php">Keluar Akun</a></button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="main-content">
            <div class="personal-data">
                <h3>Data Pribadi</h3>
                <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
                <?php if (!empty($success)) echo '<p class="success">'.htmlspecialchars($success).'</p>'; ?>
                <form id="personal-data-form" action="akunadmin.php" method="post" enctype="multipart/form-data">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>">
                    
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    
                    <label for="gender">Kelamin</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
                    
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>">
                    
                    <label for="alamat">Kota Tempat Tinggal</label>
                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($alamat); ?>">
                    
                    <label for="gambar">Gambar</label>
                    <input type="file" id="gambar" name="gambar">
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    
                    <label for="phone">No Handphone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    
                    <div class="buttons">
                        <button type="submit" name="update" class="blue-button">Update Profile</button>
                        <button type="submit" name="delete" class="red-button" onclick="return confirm('Are you sure you want to delete your account?');">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <p>PT WISATA INDONESIA | Jl ciputra world wezezeze</p>
    </footer>
    <script>
        document.getElementById('dashboard').onclick = function() {
            window.location.href = 'akunAdmin.php';
        };
    </script>
</body>
</html>
