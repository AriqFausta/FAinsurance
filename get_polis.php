<?php
include 'config.php';

$nomor = $_GET['nomor_polis'] ?? '';
$nomor = trim($nomor);

if ($nomor === '') {
    echo json_encode(['status'=>'error','message'=>'Nomor polis kosong']);
    exit;
}

$sql = "SELECT p.id_polis, n.nama, j.jenis, 
        IF(EXISTS(SELECT 1 FROM pembayaran WHERE id_polis=p.id_polis), 'Lunas', 'Belum Lunas') as status_pembayaran
        FROM polis p
        JOIN nasabah n ON p.id_nasabah = n.id_nasabah
        JOIN jenis_asuransi j ON p.id_jenis = j.id_jenis
        WHERE p.id_polis = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $nomor);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    echo json_encode(['status'=>'ok','polis'=>$row]);
} else {
    echo json_encode(['status'=>'notfound']);
}
