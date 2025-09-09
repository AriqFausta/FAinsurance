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
        .container-atas, .container-bawah {
            background: rgba(255,255,255,0.92); 
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
        input[type="text"]#nomor_polis {
            width: 100%;
            max-width: 600px;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solidrgb(0, 0, 0);
            background: rgba(255,255,255,0.7); 
            font-size: 18px;
            margin-top: 10px;
            margin-bottom: 0px;
            box-sizing: border-box;
            transition: border 0.2s, box-shadow 0.2s;
        }
        input[type="text"]#nomor_polis:focus {
            border: 1.5px solidrgb(0, 251, 255);
            box-shadow: 0 0 6pxrgb(0, 234, 255);
            outline: none;
        }
        .find-but {
            height: 40px;
            width: auto;
            padding: 5px;
            background-color : #ffff;
            border: none;
            border-radius: 6px; 
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            outline: 1.5px solid black;
            font-family: inherit;
        }
        .find-but:hover {
            background-color: #e3f0ff;
            outline: 2px solid #0056b3;
        }

        
    </style>
</head>
<body>
    <div class="container-atas">
        <form id="formPolis" autocomplete="off" style="display: flex; align-items: flex-end; gap: 12px;">
            <div style="flex:1;">
                <label for="nomor_polis"><b>Masukkan Nomor Polis:</b></label>
                <input type="text" id="nomor_polis" name="nomor_polis" required>
            </div>
            <button type="submit" class="find-but" style="margin-bottom:4px;">
                <img src="icon/search.png" alt="Cari" style="width: 20px; height: 20px;">
            </button>
        </form>
    </div>
    <div class="container-bawah" id="container_bawah">
        <!-- Data polis akan muncul di sini -->
    </div>
    <script>
        document.getElementById('formPolis').addEventListener('submit', function(e) {
            e.preventDefault();
            const nomor = document.getElementById('nomor_polis').value.trim();
            const container = document.getElementById('container_bawah');
            // AJAX ke backend untuk ambil data polis dari database
            fetch('get_polis.php?nomor_polis=' + encodeURIComponent(nomor))
                .then(res => res.json())
                .then(data => {
                    if (data && data.status === 'ok') {
                        const polis = data.polis;
                        container.innerHTML = `
                            <h2>Data Polis</h2>
                            <table>
                                <tr><th>Nomor Polis</th><td>${polis.id_polis}</td></tr>
                                <tr><th>Nama</th><td>${polis.nama}</td></tr>
                                <tr><th>Produk</th><td>${polis.jenis}</td></tr>
                                <tr><th>Status Pembayaran</th><td>${polis.status_pembayaran}</td></tr>
                            </table>
                            <div style="margin-top:18px;">
                                <button id="claimBtn" style="padding:10px 24px;background:#007bff;color:#fff;border:none;border-radius:6px;cursor:pointer;">Claim</button>
                            </div>
                            <div id="claim_result" style="margin-top:12px;"></div>
                        `;
                        document.getElementById('claimBtn').onclick = function() {
                            if (confirm('Apakah Anda yakin ingin melakukan claim untuk polis ini?')) {
                                // Kirim request claim ke backend
                                fetch('claim_action.php', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify({id_polis: polis.id_polis})
                                })
                                .then(res => res.json())
                                .then(result => {
                                    document.getElementById('claim_result').innerHTML = result.status === 'ok'
                                        ? '<span style="color:green;">Claim berhasil diajukan!</span>'
                                        : '<span style="color:red;">' + result.message + '</span>';
                                })
                                .catch(() => {
                                    document.getElementById('claim_result').innerHTML = '<span style="color:red;">Terjadi kesalahan saat mengajukan claim.</span>';
                                });
                            }
                        }
                    } else {
                        container.innerHTML = `<div style="color:red;">Nomor polis tidak ditemukan !</div>`;
                    }
                })
                .catch(() => {
                    container.innerHTML = `<div style="color:red;">Terjadi kesalahan koneksi ke server.</div>`;
                });
        });
    </script>
</body>
</html>