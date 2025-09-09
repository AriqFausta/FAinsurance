<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_polis = $data['id_polis'] ?? '';

if ($id_polis === '') {
    echo json_encode(['status'=>'error','message'=>'ID polis kosong']);
    exit;
}

$stmt = $conn->prepare("SELECT id_claim FROM claim WHERE id_polis = ?");
$stmt->bind_param('s', $id_polis);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['status'=>'error','message'=>'Claim sudah diajukan!']);
    exit;
}
$stmt->close();

$tanggal = date('Y-m-d');
$jumlah = 0;
$status = 'REQ';

$stmt = $conn->prepare("INSERT INTO claim (tanggal_claim, jumlah_claim, status_claim, id_polis) VALUES (?, ?, ?, ?)");
$stmt->bind_param('siss', $tanggal, $jumlah, $status, $id_polis);

if ($stmt->execute()) {
    echo json_encode(['status'=>'ok']);
} else {
    echo json_encode(['status'=>'error','message'=>'Gagal insert claim']);
}
    echo json_encode(['status'=>'error','message'=>'Gagal insert claim']);
}
