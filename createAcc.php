
<?php
include 'koneksi.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan data dari form
    $fullname = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tanggalLahir = $_POST['tanggal_lahir'];
    $phone = $_POST['phone'];
    $accountType = $_POST['akun'];
    $referralCode = isset($_POST['referral_code']) ? $_POST['referral_code'] : null;


    // Set referral code ke 'ADMIN123' jika jenis akun adalah admin
    if ($accountType === 'Admin') {
        $referralCode = 'ADMIN123';
    }
    $target_dir = "ppUser/";
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);

    // Validasi gambar
    if ($check !== false) {
        // Validasi ukuran gambar
        if ($_FILES["gambar"]["size"] > 2000000) {
            $error = "Maaf, file Anda terlalu besar. Maksimum ukuran adalah 2MB.";
            $uploadOk = 0;
        }
        // Validasi tipe gambar
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
        }
    } else {
        $error = "File bukan gambar.";
        $uploadOk = 0;
    }

    // Jika tidak ada kesalahan pada pengunggahan gambar
    if ($uploadOk == 1) {
        // Jika jenis akun adalah 'Admin', pastikan referral code tidak kosong
        if ($accountType == 'Admin' && empty($referralCode)) {
            $error = "Maaf, referral code wajib diisi untuk jenis akun Admin.";
        } else {
            // Pindahkan file gambar ke direktori yang ditentukan
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Simpan data pengguna ke dalam database
               // Simpan data pengguna ke dalam database
    $stmt = $conn->prepare("INSERT INTO person (nama_lengkap, username, email, alamat, password, gender, tanggal_lahir, phone, akun, referral_code, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Mengubah binding parameter menjadi 's' untuk string pada kolom referral_code
    $stmt->bind_param("sssssssssss", $fullname, $username, $email, $alamat, $password, $gender, $tanggalLahir, $phone, $accountType, $referralCode, $target_file);

                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $error = "Maaf, terjadi kesalahan saat mengunggah file Anda.";
            }
        }
    }

    

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #3AA6B9, #FFD0D0, #FF9EAA, #F9F9E0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container,
        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        .login-container h2,
        .register-container h2 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #3A57E8;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #2C47C6;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        p {
            margin-top: 15px;
        }

        p a {
            color: #3A57E8;
            text-decoration: none;
            font-weight: bold;
        }

        p a:hover {
            color: #2C47C6;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if (isset($error)) echo '<p class="error">'.$error.'</p>'; ?>
        <form action="createAcc.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="alamat">alamat</label>
                <input type="alamat" id="alamat" name="alamat" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="form-group">
                <label for="akun">Akun</label>
                <select id="akun" name="akun" required onchange="checkAccountType(this.value)">
                    <option value="">Select Account Type</option>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="form-group" id="referral_code_div" style="display: none;">
                <label for="referral_code">Referral Code</label>
                <input type="text" id="referral_code" name="referral_code">
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input type="file" id="gambar" name="gambar">
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script>
        function checkAccountType(value) {
            var referralDiv = document.getElementById('referral_code_div');
            if (value === 'Admin') {
                referralDiv.style.display = 'block';
            } else {
                referralDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>
