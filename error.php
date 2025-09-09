<?php 
include 'config.php';
//if ($conn = mysqli_connect($host, $username, $password, $database)) {
//    header("Location: index.php");
//    exit();
//}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color:rgba(0, 84, 167, 1);
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            color : #fff;
        }
        .Logo {
            display : block;
            width: 300px;
            height: auto;
            display: block;
            margin: 100px auto 20px auto;
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.35);
        }
        .text-center {
            display: block;
            text-align: center;
            font-size: 50px;
            margin-top: 20px;
        }
        @media (max-width: 700px) {
            .Logo { width: 140px; }
            .text-center { font-size: 24px; }
        }
        @media (max-width: 400px) {
            .Logo { width: 80px; }
            .text-center { font-size: 14px; }
        }
    </style>
</head>
<body>
    <image src="asset/insurance_logo.png" alt="Logo" class="Logo"></image>
    <br>
    <h1 class="text-center">
        Maaf Web Sedang Mengalami Gangguan, Silahkan Coba Beberapa Saat Lagi :D
    </h1>
</body>
</html>