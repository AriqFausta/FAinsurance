<?php
header('Content-Type: application/json');
session_start();
include 'config.php';

file_put_contents('session_debug.txt', print_r($_SESSION, true));

$data = json_decode(file_get_contents('php://input'), true);

$premi_bulanan = 100000;
$id_jenis = intval($data['id_jenis']);
$deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);
$metode_bayar = mysqli_real_escape_string($conn, $data['metode_bayar']);
$id_nasabah = isset($_SESSION['user_id']) ? strval($_SESSION['user_id']) : null;

function generateId($prefix, $conn, $table, $field) {
    do {
        $id = $prefix . substr(str_shuffle('0123456789'), 0, 10);
        $cek = mysqli_query($conn, "SELECT 1 FROM $table WHERE $field='$id'");
    } while(mysqli_num_rows($cek) > 0);
    return $id;
}

$id_pembayaran = generateId('PE', $conn, 'pembayaran', 'id_pembayaran');
$id_polis = generateId('P', $conn, 'polis', 'id_polis');
$tanggal = date('Y-m-d');

$sql_bayar = "INSERT INTO pembayaran (id_pembayaran, tanggal_bayar, jumlah, metode_bayar, id_nasabah, id_jenis, id_polis) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql_bayar);
if (!$stmt) {
    echo json_encode(['status'=>'fail', 'msg'=>'Prepare pembayaran: '.mysqli_error($conn)]);
    exit;
}
mysqli_stmt_bind_param($stmt, "ssissis", $id_pembayaran, $tanggal, $premi_bulanan, $metode_bayar, $id_nasabah, $id_jenis, $id_polis);
$res = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$sql_polis = "INSERT INTO polis (id_polis, tanggal_terbit, premi_bulanan, id_nasabah, id_jenis, deskripsi) VALUES (?, ?, ?, ?, ?, ?)";
$stmt2 = mysqli_prepare($conn, $sql_polis);
if (!$stmt2) {
    echo json_encode(['status'=>'fail', 'msg'=>'Prepare polis: '.mysqli_error($conn)]);
    exit;
}
mysqli_stmt_bind_param($stmt2, "ssisis", $id_polis, $tanggal, $premi_bulanan, $id_nasabah, $id_jenis, $deskripsi);
$res2 = mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);

if ($res && $res2) {
    echo json_encode([
        'status' => 'ok',
        'harga_premi' => $premi_bulanan
    ]);
} else {
    echo json_encode(['status'=>'fail', 'msg'=>mysqli_error($conn)]);
}

mysqli_close($conn);
