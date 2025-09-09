<?php
include 'config.php';
session_start();

// Tambahkan pengecekan role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit;
}

include 'header.php'; 

// Ambil data nasabah dari session
$id_nasabah = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$nama = $email = $alamat = $tanggal_lahir = $no_ktp = $nomor_telepon = $role = "";

if ($id_nasabah) {
    $sql_nasabah = "SELECT * FROM nasabah WHERE id_nasabah = ?";
    $stmt_nasabah = mysqli_prepare($conn, $sql_nasabah);
    mysqli_stmt_bind_param($stmt_nasabah, "s", $id_nasabah);
    mysqli_stmt_execute($stmt_nasabah);
    $result_nasabah = mysqli_stmt_get_result($stmt_nasabah);
    if ($row_nasabah = mysqli_fetch_assoc($result_nasabah)) {
        $nama = $row_nasabah['nama'];
        $email = $row_nasabah['email'];
        $alamat = $row_nasabah['alamat'];
        $tanggal_lahir = $row_nasabah['tanggal_lahir'];
        $no_ktp = $row_nasabah['no_ktp'];
        $nomor_telepon = $row_nasabah['no_telp'];
        $role = $row_nasabah['role'];
    }
    mysqli_stmt_close($stmt_nasabah);
}

// Query polis milik nasabah (user)
$sql = "SELECT p.id_polis, j.jenis, p.deskripsi FROM polis p 
        JOIN jenis_asuransi j ON p.id_jenis = j.id_jenis
        WHERE p.id_nasabah = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id_nasabah);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style> 
        body {
            font-family: "Oswald", sans-serif;
            background-image : url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container-atas, .container-bawah, .container-tengah {
            background: rgba(255,255,255,0.92); /* white with slight transparency */
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 32px 40px;
            margin: 24px 0;
            width: 90%;
            max-width: 600px;
        }
        .container-tengah h1 {
            margin-top: 0;
            color : black;
        }
        .container-atas h1, .container-bawah h2 {
            margin-top: 0;
            color : black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            background: #f8fafd;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        th {
            background: #e3f0ff;
            color: #0056b3;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .tombol-tombolan {
            margin-top: 18px;
            display: flex;
            gap: 12px;
        }
        .tombol-tombolan a {
            padding: 8px 18px;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .tombol-tombolan a:hover {
            background: #0056b3;
        }
        @media (max-width: 700px) {
            .container-atas, .container-bawah, .container-tengah {
                padding: 16px 8px;
                width: 98%;
                max-width: 100vw;
            }
            table, th, td {
                font-size: 13px;
                word-break: break-word;
            }
            .tombol-tombolan a {
                font-size: 14px;
                padding: 6px 10px;
            }
        }
        @media (max-width: 450px) {
            .container-atas, .container-bawah, .container-tengah {
                padding: 8px 2px;
            }
            h1, h2 { font-size: 1.1em; }
        }
    </style>
</head>
<body>
    <div class="container-atas">
        <div>
            <h1>Selamat Datang <?= htmlspecialchars($nama) ?></h1>
        </div>
    </div>
    <div class="container-tengah">
        <h1> Profile Anda</h1>
            <div class="section">
                <p>Nama: <?= htmlspecialchars($nama) ?></p>
                <p>Email: <?= htmlspecialchars($email) ?></p>
                <p>Alamat: <?= htmlspecialchars($alamat) ?></p>
                <p>Tanggal Lahir: <?= htmlspecialchars($tanggal_lahir) ?></p>
                <p>No. KTP: <?= htmlspecialchars($no_ktp) ?></p>
                <p>Nomor Telepon: <?= htmlspecialchars($nomor_telepon) ?></p>
            </div>
            <div class="tombol-tombolan">
                <a href="edit.php">Edit Profile</a>
            </div>
    </div>
    <div class="container-bawah">
        <div>
            <h2>Polis Anda</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Polis</th>
                        <th>Jenis Asuransi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_polis']) ?></td>
                                <td><?= htmlspecialchars($row['jenis']) ?></td>
                                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">anda tidak punya polis</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="tombol-tombolan">
                <a href="claim.php">claim</a>
                <a href="tambah_asuransi.php">tambah</a>
            </div>
        </div>
    </div>
</body>
</html>