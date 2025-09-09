<?php
include 'config.php';
include 'header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style> 
        body {
            font-family: Arial, sans-serif;
            background-image : url('asset/background.png');
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
            margin: 24px 0;
            width: 90%;
            max-width: 600px;
        }
        .container-atas h1 {
            margin-top: 0;
            color : black;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            width: 100px;
        }
        .section {
            margin-bottom: 30px;
            color : #ffffff;
        }
        h2 {
            color: #4af346;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        ul {
            padding-left: 20px;
        }
        li::marker {
            color: #4af346;
        }
        .contact-info {
            margin-top: 30px;
        }
        .icon {
            margin-right: 8px;
        }
        @media (max-width: 700px) {
            .container-atas {
                padding: 16px 8px;
                width: 98%;
                max-width: 100vw;
            }
            .logo img { width: 60px; }
            h1, h2 { font-size: 1.1em; }
        }
        @media (max-width: 450px) {
            .container-atas {
                padding: 8px 2px;
            }
        }
    </style>
</head>
<body>
    <div class="container-atas">
        <div>
            <div class="logo">
                <img src="asset/insurance_logo.png" alt="Logo FA Insurance" />
            </div>
            <div class="section">
                <h1>DISCLAIMER</h1>
                <p>WEB INI BUKAN WEB ASURANSI RESMI DAN HANYA DIGUNAKAN OLEH SAYA UNTUK BELAJAR MEMBUAT WEB!!!</p>
            </div>
            <div class="section">
                <p>FA INSURANCE didirikan pada 25 Mei 2025, berlandaskan visi bersama untuk memberikan solusi asuransi yang mudah diakses, transparan, dan berorientasi pada pelanggan. Berakar pada prinsip integritas dan inovasi yang kuat, perusahaan kami dibangun untuk memenuhi kebutuhan individu, keluarga, dan bisnis di dunia yang terus berkembang.</p>
                <p>Pondasi kami terletak pada keahlian industri yang mendalam, transformasi digital, dan komitmen terhadap hubungan jangka panjang dengan klien. Sejak awal, kami fokus menawarkan perlindungan yang disesuaikan untuk memberikan ketenangan dan perlindungan finansial atas ketidakpastian hidup.</p>
            </div>
            <div class="section">
                <h2>Misi Kami</h2>
                <p>Menyediakan layanan asuransi yang andal dan fleksibel agar klien kami dapat hidup dan bekerja dengan percaya diri, karena merasa terlindungi.</p>
            </div>
            <div class="section">
                <h2>Visi Kami</h2>
                <p>Menjadi kekuatan terdepan di industri asuransi melalui kepercayaan, teknologi, dan transparansi.</p>
            </div>
            <div class="section">
                <h2>Layanan Kami</h2>
                <ul>
                    <li>Asuransi Jiwa dan Kesehatan</li>
                    <li>Asuransi Kendaraan dan Properti</li>
                    <li>Perlindungan Bisnis dan Komersial</li>
                    <li>Paket yang Dapat Disesuaikan untuk Individu dan Perusahaan</li>
                </ul>
            </div>
            <div class="section">
                <h2>Dipercaya Oleh</h2>
                <p>Sejak berdiri, [Nama Perusahaan Anda] telah dipercaya oleh lebih dari [masukkan jumlah] klien, termasuk individu, usaha kecil, dan perusahaan besar. Kami bangga menjadi penyedia asuransi pilihan untuk:</p>
                <ul>
                    <li>PT. Penjaga Dunia</li>
                    <li>PT. ptan</li>
                </ul>
                <p>Kami terus berupaya menjaga reputasi melalui layanan yang andal, praktik etis, dan dukungan klaim yang cepat.</p>
            </div>
            <div class="section contact-info">
                <h2>Hubungi Kami</h2>
                <p><span class="icon">📍</span>Alamat: Universitas Gadjah Mada, Vokasi, Departemen Teknik Elektro dan Informatika</p>
                <p><span class="icon">📞</span>Telepon: Ariq Fausta Djohar</p>
                <p><span class="icon">✉️</span>Email: ariqfausta@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>