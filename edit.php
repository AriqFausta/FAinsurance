<?php
session_start();
include 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_nasabah = $_SESSION['user_id'];

$sql = "SELECT * FROM nasabah WHERE id_nasabah = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_nasabah);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$nama = $user['nama'] ?? '';
$email = $user['email'] ?? '';
$alamat = $user['alamat'] ?? '';
$tanggal_lahir = $user['tanggal_lahir'] ?? '';
$no_ktp = $user['no_ktp'] ?? '';
$no_telp = $user['no_telp'] ?? '';

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $no_ktp = $_POST['no_ktp'] ?? '';
    $no_telp = $_POST['no_telp'] ?? '';

    $sql_update = "UPDATE nasabah SET nama=?, email=?, alamat=?, tanggal_lahir=?, no_ktp=?, no_telp=? WHERE id_nasabah=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssiss", $nama, $email, $alamat, $tanggal_lahir, $no_ktp, $no_telp, $id_nasabah);
    if ($stmt_update->execute()) {
        $success = "Profile berhasil diupdate.";
    } else {
        $error = "Gagal update profile.";
    }
    $stmt_update->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container-atas {
            background: rgba(85, 102, 217, 0.92);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 32px 40px;
            margin: 40px 0 24px 0;
            width: 90%;
            max-width: 600px;
        }
        .container-atas h1 {
            margin-top: 0;
            color: #fff;
            letter-spacing: 1px;
            text-align: center;
        }
        form {
            margin-top: 18px;
        }
        label {
            color: #e3f0ff;
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1.5px solid #bfcfff;
            background: rgba(255,255,255,0.85);
            font-size: 16px;
            margin-bottom: 18px;
            box-sizing: border-box;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus {
            border: 2px solid #4af346;
            box-shadow: 0 0 6px #4af34644;
            outline: none;
        }
        button[type="submit"] {
            padding: 10px 28px;
            background: #e3f0ff;
            color: #0056b3;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            border: 1px solid #bfcfff;
            transition: background 0.2s, color 0.2s;
        }
        button[type="submit"]:hover {
            background: #0056b3;
            color: #fff;
        }
        a {
            padding: 10px 28px;
            background: #e3f0ff;
            color: #0056b3;
            border-radius: 6px;
            text-decoration: none;
            margin-left: 8px;
            font-weight: bold;
            border: 1px solid #bfcfff;
            transition: background 0.2s, color 0.2s;
        }
        a:hover {
            background: #0056b3;
            color: #fff;
        }
        .msg-success {
            color: #4af346;
            background: #eaffea;
            border: 1px solid #4af346;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 18px;
            text-align: center;
        }
        .msg-error {
            color: #ff3b3b;
            background: #ffeaea;
            border: 1px solid #ff3b3b;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 18px;
            text-align: center;
        }
        @media (max-width: 700px) {
            .container-atas {
                padding: 16px 8px;
                width: 98%;
                max-width: 100vw;
            }
            input, button, label {
                font-size: 15px !important;
            }
        }
        @media (max-width: 450px) {
            .container-atas {
                padding: 8px 2px;
            }
            h1 { font-size: 1.1em; }
        }
    </style>
</head>
<body>
    <div class="container-atas">
        <h1>Edit Profile</h1>
        <?php if ($success): ?>
            <div class="msg-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="msg-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <div>
                <label>Nama:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div>
                <label>Alamat:</label>
                <input type="text" name="alamat" value="<?= htmlspecialchars($alamat) ?>" required>
            </div>
            <div>
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($tanggal_lahir) ?>" required>
            </div>
            <div>
                <label>No. KTP:</label>
                <input type="text" name="no_ktp" value="<?= htmlspecialchars($no_ktp) ?>" required>
            </div>
            <div>
                <label>No. Telepon:</label>
                <input type="text" name="no_telp" value="<?= htmlspecialchars($no_telp) ?>" required>
            </div>
            <div style="margin-top:16px; text-align:center;">
                <button type="submit">Simpan</button>
                <a href="dashboard.php">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>