<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi ke database benar

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier']; // Bisa username atau email
    $password = $_POST['password'];

    // Mengambil data user berdasarkan username atau email
    $stmt = $koneksi->prepare("SELECT username, password, referral_code FROM person WHERE username = ? OR email = ?");
    if ($stmt === false) {
        die("Prepare statement failed: " . $koneksi->error);
    }

    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            if ($row['referral_code'] === 'ADMIN123') {
                $_SESSION['username'] = $row['username'];
                header("Location: akunadmin.php");
            } else {
                $_SESSION['username'] = $row['username'];
                header("Location: akunuser.php");
            }
            exit();
        } else {
            $error = "Invalid login credentials";
        }
    } else {
        $error = "Invalid login credentials";
    }

    $stmt->close();
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loo.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="identifier">Username or Email</label>
                <input type="text" id="identifier" name="identifier" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="createAcc.php">Register</a></p>
    </div>
</body>
</html>
