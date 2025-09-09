<?php
include 'config.php';
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT id_nasabah, password, nama, role FROM nasabah WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && verifyPassword($password, $user['password'])) { // Ganti pengecekan password
        $_SESSION['user_id'] = $user['id_nasabah'];
        $_SESSION['role'] = $user['role'];
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
            exit;
        } else {
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Email atau password salah!";
    }
    mysqli_stmt_close($stmt);
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
            font-family: Arial, sans-serif;
            background-image : url('asset/background.png');
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left {
            background-color: #007bff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 70px 85px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .right {
            background: #fff;
            min-width: 200px;
            padding: 30px 25px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .Logo {
            width: 120px;
            height: auto;
        }
        .Welcome {
            margin-bottom: 24px;
            color: #2d3e50;
            font-size: 1.5em;
            text-align: center;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            color: #34495e;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        button[type="submit"]:hover {
            background: #0056b3;
        }
        .login-wrapper {
            display: flex;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            max-width: 600px;
            margin: auto;
        }
        .signup-link {
            text-align: center;
            margin-top: 12px;
            font-size: 0.97em;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 700px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 90vw;
            }
            .left, .right {
                border-radius: 0;
                padding: 30px 15px;
            }
            .left {
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                border-bottom-left-radius: 0;
            }
            .right {
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top-right-radius: 0;
            }
        }
        @media (max-width: 400px) {
            .Logo { width: 50px; }
            .Welcome { font-size: 1em; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="left">
            <img src="asset/insurance_logo.png" alt="Logo" class="Logo">
        </div>
        <div class="right">
            <h1 class="Welcome"> Selamat Datang Kembali!</h1>
            <?php if ($error): ?>
                <div style="color:red;text-align:center;margin-bottom:10px;"><?= $error ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Login</button>
                </div>
                <div class="signup-link">
                    <p>Belum punya akun? <a href="signup.php">Daftar Sekarang</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php 
    mysqli_close($conn);
?>