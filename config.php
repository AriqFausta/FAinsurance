<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "projek_asuransi";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($host, $username, $password, $database);
} catch (mysqli_sql_exception $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

function generateNasabahID($conn) {
    do {
        $randomNumber = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $id = 'N' . $randomNumber;
        $check = mysqli_query($conn, "SELECT id_nasabah FROM nasabah WHERE id_nasabah='$id'");
    } while(mysqli_num_rows($check) > 0);

    return $id;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}


?>