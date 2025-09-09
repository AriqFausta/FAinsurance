<?php
session_start();
include 'config.php';
include 'header.php';


if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}


function getSearch($key) {
    return isset($_GET[$key]) ? trim($_GET[$key]) : '';
}
$search_nasabah = getSearch('search_nasabah');
$search_claim = getSearch('search_claim');
$search_jenis = getSearch('search_jenis');
$search_polis = getSearch('search_polis');


$q_nasabah = "SELECT * FROM nasabah WHERE 1";
if ($search_nasabah) {
    $q_nasabah .= " AND (nama LIKE '%$search_nasabah%' OR email LIKE '%$search_nasabah%' OR id_nasabah LIKE '%$search_nasabah%')";
}
$res_nasabah = $conn->query($q_nasabah);


$q_claim = "SELECT c.*, n.nama, p.id_polis FROM claim c LEFT JOIN polis p ON c.id_polis=p.id_polis LEFT JOIN nasabah n ON p.id_nasabah=n.id_nasabah WHERE 1";
if ($search_claim) {
    $q_claim .= " AND (c.id_claim LIKE '%$search_claim%' OR n.nama LIKE '%$search_claim%' OR c.id_polis LIKE '%$search_claim%')";
}
$res_claim = $conn->query($q_claim);


$q_jenis = "SELECT * FROM jenis_asuransi WHERE 1";
if ($search_jenis) {
    $q_jenis .= " AND (jenis LIKE '%$search_jenis%' OR id_jenis LIKE '%$search_jenis%')";
}
$res_jenis = $conn->query($q_jenis);


$q_polis = "SELECT p.*, n.nama, j.jenis FROM polis p LEFT JOIN nasabah n ON p.id_nasabah=n.id_nasabah LEFT JOIN jenis_asuransi j ON p.id_jenis=j.id_jenis WHERE 1";
if ($search_polis) {
    $q_polis .= " AND (p.id_polis LIKE '%$search_polis%' OR n.nama LIKE '%$search_polis%' OR j.jenis LIKE '%$search_polis%')";
}
$res_polis = $conn->query($q_polis);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_claim'])) {
    $id_claim = $_POST['accept_claim'];
    $stmt = $conn->prepare("UPDATE claim SET status_claim='accepted' WHERE id_claim=?");
    $stmt->bind_param("i", $id_claim);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_type'], $_POST['delete_id'])) {
    $type = $_POST['delete_type'];
    $id = $_POST['delete_id'];
    if ($type === 'nasabah') {
        $stmt = $conn->prepare("DELETE FROM nasabah WHERE id_nasabah=?");
        $stmt->bind_param("s", $id);
    } elseif ($type === 'claim') {
        $stmt = $conn->prepare("DELETE FROM claim WHERE id_claim=?");
        $stmt->bind_param("i", $id);
    } elseif ($type === 'jenis') {
        $stmt = $conn->prepare("DELETE FROM jenis_asuransi WHERE id_jenis=?");
        $stmt->bind_param("i", $id);
    } elseif ($type === 'polis') {
        $stmt = $conn->prepare("DELETE FROM polis WHERE id_polis=?");
        $stmt->bind_param("s", $id);
    }
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .admin-container {
            background: rgba(255,255,255,0.96);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 32px 40px;
            margin: 40px auto 24px auto;
            width: 95%;
            max-width: 1200px;
            border: none;
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-top: 0;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            justify-content: center;
        }
        .tab-btn {
            padding: 10px 28px;
            background: #e3f0ff;
            color: #0056b3;
            border-radius: 6px 6px 0 0;
            border: 1px solid #bfcfff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            outline: none;
        }
        .tab-btn.active, .tab-btn:hover {
            background: #007bff;
            color: #fff;
        }
        .tab-content {
            display: none;
            animation: fadeIn 0.3s;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0;}
            to { opacity: 1;}
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
        .search-bar {
            margin-bottom: 10px;
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .search-bar input[type="text"] {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #bfcfff;
            font-size: 16px;
            width: 220px;
        }
        .search-bar button {
            padding: 8px 18px;
            background: #4af346;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-bar button:hover {
            background: #007bff;
        }
        .action-btn {
            padding: 6px 14px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-right: 4px;
            transition: background 0.2s;
        }
        .action-btn:hover {
            background: #4af346;
            color: #fff;
        }
        .msg-success, .msg-error, .form-tambah-jenis { display: none; }
        @media (max-width: 900px) {
            .admin-container { padding: 16px 4px; }
            table, th, td { font-size: 13px; }
        }
    </style>
    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
            document.getElementById('tab-btn-' + tab).classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        }
        window.onload = function() {
            showTab('nasabah');
        };

        // Accept claim
        function acceptClaim(id) {
            if (confirm('Terima claim ini?')) {
                fetch('admin.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'accept_claim=' + encodeURIComponent(id)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') location.reload();
                });
            }
        }

        // Hapus data
        function deleteData(type, id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                fetch('admin.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'delete_type=' + encodeURIComponent(type) + '&delete_id=' + encodeURIComponent(id)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') location.reload();
                });
            }
        }
    </script>
</head>
<body>
    <div class="admin-container">
        <div class="tabs">
            <button class="tab-btn" id="tab-btn-nasabah" onclick="showTab('nasabah')">Nasabah</button>
            <button class="tab-btn" id="tab-btn-claim" onclick="showTab('claim')">Claim</button>
            <button class="tab-btn" id="tab-btn-jenis" onclick="showTab('jenis')">Jenis Asuransi</button>
            <button class="tab-btn" id="tab-btn-polis" onclick="showTab('polis')">Polis</button>
        </div>
        <!-- Nasabah -->
        <div class="tab-content" id="tab-nasabah">
            <form class="search-bar" method="get">
                <input type="text" name="search_nasabah" placeholder="Cari nasabah..." value="<?= htmlspecialchars($search_nasabah) ?>">
                <button type="submit">Cari</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Nasabah</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Tanggal Lahir</th>
                        <th>No. KTP</th>
                        <th>No. Telp</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res_nasabah->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_nasabah']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                            <td><?= htmlspecialchars($row['no_ktp']) ?></td>
                            <td><?= htmlspecialchars($row['no_telp']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td>
                                <button class="action-btn" onclick="deleteData('nasabah','<?= $row['id_nasabah'] ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- Claim -->
        <div class="tab-content" id="tab-claim">
            <form class="search-bar" method="get">
                <input type="text" name="search_claim" placeholder="Cari claim..." value="<?= htmlspecialchars($search_claim) ?>">
                <button type="submit">Cari</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Claim</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>ID Polis</th>
                        <th>Nama Nasabah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res_claim->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_claim']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_claim']) ?></td>
                            <td><?= htmlspecialchars($row['jumlah_claim']) ?></td>
                            <td><?= htmlspecialchars($row['status_claim']) ?></td>
                            <td><?= htmlspecialchars($row['id_polis']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td>
                                <?php if ($row['status_claim'] !== 'accepted'): ?>
                                    <button class="action-btn" onclick="acceptClaim('<?= $row['id_claim'] ?>')">Accept</button>
                                <?php endif; ?>
                                <button class="action-btn" onclick="deleteData('claim','<?= $row['id_claim'] ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- Jenis Asuransi -->
        <div class="tab-content" id="tab-jenis">
            <form class="search-bar" method="get">
                <input type="text" name="search_jenis" placeholder="Cari jenis asuransi..." value="<?= htmlspecialchars($search_jenis) ?>">
                <button type="submit">Cari</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Jenis</th>
                        <th>Jenis Asuransi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res_jenis->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_jenis']) ?></td>
                            <td><?= htmlspecialchars($row['jenis']) ?></td>
                            <td>
                                <button class="action-btn" onclick="deleteData('jenis','<?= $row['id_jenis'] ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <!-- Polis -->
        <div class="tab-content" id="tab-polis">
            <form class="search-bar" method="get">
                <input type="text" name="search_polis" placeholder="Cari polis..." value="<?= htmlspecialchars($search_polis) ?>">
                <button type="submit">Cari</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Polis</th>
                        <th>Nama Nasabah</th>
                        <th>Jenis Asuransi</th>
                        <th>Premi Bulanan</th>
                        <th>Tanggal Terbit</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res_polis->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_polis']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['jenis']) ?></td>
                            <td><?= htmlspecialchars($row['premi_bulanan']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_terbit']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td>
                                <button class="action-btn" onclick="deleteData('polis','<?= $row['id_polis'] ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
        <!-- Polis -->
        <div class="tab-content" id="tab-polis">
            <form class="search-bar" method="get">
                <input type="text" name="search_polis" placeholder="Cari polis..." value="<?= htmlspecialchars($search_polis) ?>">
                <button type="submit">Cari</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID Polis</th>
                        <th>Nama Nasabah</th>
                        <th>Jenis Asuransi</th>
                        <th>Premi Bulanan</th>
                        <th>Tanggal Terbit</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $res_polis->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_polis']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['jenis']) ?></td>
                            <td><?= htmlspecialchars($row['premi_bulanan']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_terbit']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td>
                                <button class="action-btn" onclick="deleteData('polis','<?= $row['id_polis'] ?>')">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
