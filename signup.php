<?php
include 'config.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $no_ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = isset($_POST['confirm_password']) ? mysqli_real_escape_string($conn, $_POST['confirm_password']) : '';
    $role = "user";

    // Validasi konfirmasi password
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama!";
    } else {
        // Cek email atau KTP sudah terdaftar
        $cek = mysqli_query($conn, "SELECT id_nasabah FROM nasabah WHERE email='$email' OR no_ktp='$no_ktp'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Email atau No. KTP sudah terdaftar!";
        } else {
            // Hash password sebelum simpan ke database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database
            $id_nasabah = generateNasabahID($conn);
            $sql = "INSERT INTO nasabah (id_nasabah, nama, alamat, tanggal_lahir, no_ktp, no_telp, password, email, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            // Ganti $password dengan $hashedPassword
            mysqli_stmt_bind_param($stmt, "ssssissss", $id_nasabah, $nama, $alamat, $tanggal_lahir, $no_ktp, $no_telp, $hashedPassword, $email, $role);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $success = "Pendaftaran berhasil! Silakan <a href='login.php'>login</a>.";
            } else {
                $error = "Pendaftaran gagal!";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nasabah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image : url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left {
            background-color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px 85px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .right {
            background: #fff;
            min-width: 200px;
            padding: 30px 25px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .Logo {
            width: 120px;
            height: auto;
        }
        .Welcome {
            margin-bottom: 24px;
            color: #2d3e50;
            font-size: 1.5em;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            color: #34495e;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background: #0056b3;
        }
        .login-wrapper {
            display: flex;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            max-width: 600px;
            margin: auto;
        }
        .signup-link {
            text-align: center;
            margin-top: 12px;
            font-size: 0.97em;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        .msg-success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        .msg-error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        @media (max-width: 700px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 90vw;
            }
            .left, .right {
                border-radius: 0;
                padding: 30px 15px;
            }
            .left {
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                border-bottom-left-radius: 0;
            }
            .right {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-right-radius: 0;
            }
        }
        @media (max-width: 400px) {
            .Logo { width: 50px; }
            .Welcome { font-size: 1em; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="left">
            <img src="asset/insurance_logo.png" alt="Logo" class="Logo">
        </div>
        <div class="right">
            <h1 class="Welcome">Buat Akun Nasabah</h1>
            <?php if ($success): ?>
                <div class="msg-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="msg-error"><?= $error ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <input type="text" id="alamat" name="alamat" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
                <div class="form-group">
                    <label for="no_ktp">No. KTP:</label>
                    <input type="number" id="no_ktp" name="no_ktp" required>
                </div>
                <div class="form-group">
                    <label for="no_telp">No. Telepon:</label>
                    <input type="number" id="no_telp" name="no_telp" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <input type="hidden" name="role" value="user">
                <div class="form-group">
                    <button type="submit">Daftar</button>
                </div>
                <div class="signup-link">
                    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php 
    mysqli_close($conn);
?>