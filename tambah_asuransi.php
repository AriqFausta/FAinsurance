<?php 
session_start();
include 'config.php';
include 'header.php';
// DEBUG: tampilkan id_nasabah jika ada
// Hapus/comment baris ini jika sudah tidak perlu debug
if (isset($_SESSION['user_id'])) {
    echo "<!-- SESSION id_nasabah: " . htmlspecialchars($_SESSION['user_id']) . " -->";
} else {
    echo "<!-- SESSION id_nasabah TIDAK ADA -->";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style> 
        body {
            font-family: "Oswald", Arial, sans-serif;
            background-image : url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container-atas, .container-bawah {
            align-items: center;
            background: rgba(255,255,255,0.92); /* white with slight transparency */
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 32px 40px;
            margin: 24px 0;
            width: 90%;
            max-width: 600px;
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
        select {
            width: 100%;
            max-width: 400px;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #b0b0b0;
            background: rgba(255,255,255,0.85);
            font-size: 16px;
            margin-top: 10px;
            margin-bottom: 0px;
            box-sizing: border-box;
            transition: border 0.2s, box-shadow 0.2s;
            outline: none;
            color: #222;
        }
        select:focus {
            border: 1.5px solid #007bff;
            box-shadow: 0 0 6px #b3d7ff;
        }
        @media (max-width: 700px) {
            .container-atas, .container-bawah {
                padding: 16px 8px;
                width: 98%;
                max-width: 100vw;
            }
            select, textarea, input, button {
                font-size: 15px !important;
            }
        }
        @media (max-width: 450px) {
            .container-atas, .container-bawah {
                padding: 8px 2px;
            }
            h2 { font-size: 1.1em; }
        }
    </style>
</head>
<body>
    <div class="container-atas">
        <div>
            <select name="kategori" id="kategori_polis">
                <option value="">-- Pilih kategori --</option>
                <option value="jiwa_kesehatan">Asuransi Jiwa & Kesehatan</option>
                <option value="kendaraan_properti">Asuransi Kendaraan & Properti</option>
                <option value="bisnis_komersial">Perlindungan Bisnis & Komersial</option>
            </select>
        </div>
    </div>
    <div class="container-bawah" id="container_bawah">
        <!-- Opsi lanjutan akan muncul di sini -->
    </div>
    <script>
        const produkData = {
            jiwa_kesehatan: [
                {
                    nama: "Asuransi Jiwa Berjangka (Term Life Insurance)",
                    deskripsi: "Memberikan manfaat kematian dalam jangka waktu tertentu (misalnya 10 atau 20 tahun)."
                },
                {
                    nama: "Asuransi Jiwa Seumur Hidup (Whole Life Insurance)",
                    deskripsi: "Perlindungan seumur hidup dengan nilai tunai yang bisa diambil."
                },
                {
                    nama: "Asuransi Kesehatan Individu",
                    deskripsi: "Menanggung biaya rawat inap, operasi, dan pengobatan penyakit kritis."
                },
                {
                    nama: "Asuransi Kesehatan Keluarga",
                    deskripsi: "Paket perlindungan kesehatan untuk seluruh anggota keluarga."
                },
                {
                    nama: "Asuransi Penyakit Kritis (Critical Illness Insurance)",
                    deskripsi: "Memberikan santunan saat terdiagnosis penyakit berat seperti kanker atau stroke."
                },
                {
                    nama: "Asuransi Rawat Inap (Hospital Cash Plan)",
                    deskripsi: "Uang tunai harian selama perawatan di rumah sakit."
                }
            ],
            kendaraan_properti: [
                {
                    nama: "Asuransi Mobil All Risk (Comprehensive)",
                    deskripsi: "Menanggung semua risiko kerusakan mobil, baik kecil maupun besar."
                },
                {
                    nama: "Asuransi TLO (Total Loss Only)",
                    deskripsi: "Mengganti rugi jika kendaraan hilang atau rusak total (>75%)."
                },
                {
                    nama: "Asuransi Motor",
                    deskripsi: "Perlindungan motor dari kecelakaan dan pencurian."
                },
                {
                    nama: "Asuransi Rumah Tinggal",
                    deskripsi: "Melindungi rumah dari kebakaran, banjir, gempa, dan pencurian."
                },
                {
                    nama: "Asuransi Properti Komersial",
                    deskripsi: "Untuk gedung perkantoran, ruko, pabrik, atau gudang dari risiko kebakaran dan kerusakan."
                },
                {
                    nama: "Asuransi Isi Rumah (Home Contents Insurance)",
                    deskripsi: "Perlindungan untuk barang-barang di dalam rumah (elektronik, furnitur, dll.)."
                }
            ],
            bisnis_komersial: [
                {
                    nama: "Asuransi Karyawan (Group Health Insurance)",
                    deskripsi: "Memberikan perlindungan kesehatan bagi karyawan perusahaan."
                },
                {
                    nama: "Asuransi Tanggung Gugat Publik (Public Liability Insurance)",
                    deskripsi: "Melindungi bisnis dari klaim pihak ketiga akibat cedera atau kerusakan."
                },
                {
                    nama: "Asuransi Kebakaran dan Bencana Alam Komersial",
                    deskripsi: "Menanggung kerusakan gedung dan aset bisnis akibat kebakaran, gempa, banjir."
                },
                {
                    nama: "Asuransi Pengangkutan Barang (Marine Cargo Insurance)",
                    deskripsi: "Proteksi barang selama pengiriman via laut, darat, atau udara."
                },
                {
                    nama: "Asuransi Gangguan Usaha (Business Interruption Insurance)",
                    deskripsi: "Memberikan penggantian pendapatan jika bisnis terganggu karena insiden besar."
                },
                {
                    nama: "Asuransi Peralatan dan Mesin Industri",
                    deskripsi: "Melindungi mesin dan alat berat dari kerusakan atau kehilangan."
                }
            ]
        };

        const select = document.getElementById('kategori_polis');
        const containerBawah = document.getElementById('container_bawah');

        function renderProdukList(kategori) {
            if (!produkData[kategori]) {
                containerBawah.innerHTML = "";
                return;
            }
            let html = `<h2>Pilih Produk Asuransi</h2><ul style="list-style:none;padding:0;">`;
            produkData[kategori].forEach((item, idx) => {
                html += `<li style="margin-bottom:12px;">
                    <button class="produk-btn" data-kategori="${kategori}" data-idx="${idx}" style="width:100%;text-align:left;padding:12px 16px;border-radius:8px;border:1px solid #e0e0e0;background:#f8fafd;cursor:pointer;font-size:16px;">
                        <b>${item.nama}</b><br><span style="font-size:13px;color:#444;">${item.deskripsi}</span>
                    </button>
                </li>`;
            });
            html += `</ul>`;
            containerBawah.innerHTML = html;

            // Tambahkan event listener untuk setiap produk
            document.querySelectorAll('.produk-btn').forEach(btn => {
                btn.onclick = function() {
                    const idx = this.getAttribute('data-idx');
                    const kategori = this.getAttribute('data-kategori');
                    renderProdukDetail(kategori, idx);
                };
            });
        }

        function renderProdukDetail(kategori, idx) {
            const produk = produkData[kategori][idx];
            let html = `
                <h2>${produk.nama}</h2>
                <p>${produk.deskripsi}</p>
                <hr>
                <label for="deskripsi_user"><b>Deskripsi Tambahan (opsional):</b></label>
                <textarea id="deskripsi_user" rows="3" style="width:100%;margin:10px 0 20px 0;padding:8px 12px;border-radius:6px;border:1px solid #b0b0b0;"></textarea>
                <label for="metode_bayar"><b>Pilih Metode Pembayaran:</b></label>
                <select id="metode_bayar" style="margin:10px 0 20px 0;padding:8px 12px;border-radius:6px;">
                    <option value="">-- Pilih --</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                    <option value="kartu_kredit">Kartu Kredit</option>
                </select>
                <br>
                <button id="lanjut_bayar" style="padding:10px 24px;background:#007bff;color:#fff;border:none;border-radius:6px;cursor:pointer;">Lanjut Pembayaran</button>
                <button id="kembali_produk" style="padding:10px 24px;background:#e0e0e0;color:#333;border:none;border-radius:6px;cursor:pointer;margin-left:10px;">Kembali</button>
            `;
            containerBawah.innerHTML = html;

            document.getElementById('kembali_produk').onclick = function() {
                renderProdukList(kategori);
            };
            document.getElementById('lanjut_bayar').onclick = function() {
                const metode = document.getElementById('metode_bayar').value;
                const deskripsi_user = document.getElementById('deskripsi_user').value;
                if (!metode) {
                    alert('Pilih metode pembayaran terlebih dahulu.');
                    return;
                }
                renderKonfirmasiPembayaran(produk, kategori, idx, metode, deskripsi_user);
            };
        }

        function renderKonfirmasiPembayaran(produk, kategori, idx, metode, deskripsi_user) {
            let metodeText = {
                transfer: "Transfer Bank",
                ewallet: "E-Wallet",
                kartu_kredit: "Kartu Kredit"
            }[metode] || metode;
            let premi_bulanan = 100000;
            let html = `
                <h2>Konfirmasi Pembayaran</h2>
                <p><b>Produk:</b> ${produk.nama}</p>
                <p><b>Deskripsi Produk:</b> ${produk.deskripsi}</p>
                <p><b>Deskripsi Anda:</b> ${deskripsi_user ? deskripsi_user : '-'}</p>
                <p><b>Harga Premi:</b> Rp. 100.000,00 / bulan</p>
                <p><b>Metode Pembayaran:</b> ${metodeText}</p>
                <div style="margin-top:24px;">
                    <button id="konfirmasi_bayar" style="padding:10px 24px;background:#007bff;color:#fff;border:none;border-radius:6px;cursor:pointer;">Konfirmasi & Bayar</button>
                    <button id="kembali_konfirmasi" style="padding:10px 24px;background:#e0e0e0;color:#333;border:none;border-radius:6px;cursor:pointer;margin-left:10px;">Kembali</button>
                </div>
                <div id="proses_status" style="margin-top:16px;"></div>
            `;
            containerBawah.innerHTML = html;

            document.getElementById('kembali_konfirmasi').onclick = function() {
                renderProdukDetail(kategori, idx);
                // Kembalikan deskripsi_user jika ingin tetap terisi (opsional)
                setTimeout(() => {
                    document.getElementById('deskripsi_user').value = deskripsi_user;
                }, 0);
            };
            document.getElementById('konfirmasi_bayar').onclick = async function() {
                document.getElementById('konfirmasi_bayar').disabled = true;
                document.getElementById('proses_status').innerHTML = "Memproses...";
                let id_jenis = getIdJenisByProduk(kategori, idx);
                let deskripsi = deskripsi_user;
                let metode_bayar = metode;
                try {
                    let res = await fetch('proses_pembelian.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            id_jenis, deskripsi, metode_bayar
                        })
                    });
                    let data = await res.json();
                    if (data.status === 'ok') {
                        renderPembayaranSukses(produk.nama, metode, data.harga_premi);
                    } else {
                        document.getElementById('proses_status').innerHTML = "Gagal menyimpan data: " + (data.msg || '');
                        document.getElementById('konfirmasi_bayar').disabled = false;
                        console.error('Backend error:', data.msg);
                    }
                } catch (err) {
                    document.getElementById('proses_status').innerHTML = "Terjadi kesalahan koneksi/server.";
                    document.getElementById('konfirmasi_bayar').disabled = false;
                    console.error('Fetch error:', err);
                }
            };
        }

        function renderPembayaranSukses(namaProduk, metode, hargaPremi) {
            document.getElementById('container_bawah').innerHTML = `
                <div class="sukses-box">
                    <h2>Pembelian Berhasil!</h2>
                    <p>Produk: <b>${namaProduk}</b></p>
                    <p>Metode Bayar: <b>${metode}</b></p>
                    <p>Premi: <b>Rp${hargaPremi.toLocaleString('id-ID')}</b></p>
                </div>
            `;
        }

        // Fungsi mapping id_jenis (dummy, sesuaikan dengan tabel jenis_asuransi di DB)
        function getIdJenisByProduk(kategori, idx) {
            // Contoh mapping, sesuaikan dengan data di tabel jenis_asuransi
            const mapping = {
                jiwa_kesehatan: [1,2,3,4,5,6],
                kendaraan_properti: [7,8,9,10,11,12],
                bisnis_komersial: [13,14,15,16,17,18]
            };
            return mapping[kategori] ? mapping[kategori][idx] : 1;
        }

        select.addEventListener('change', function() {
            renderProdukList(this.value);
        });

        // (Opsional) renderProdukList otomatis jika sudah ada value
        // if (select.value) renderProdukList(select.value);
    </script>
</body>
</html>