<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Aplikasi - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .contact {
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            color: white;
            border-radius: 5px;
        }
        .contact a {
            color: #fff;
            text-decoration: underline;
        }
        .contact a:hover {
            color: #ffdd57;
        }
        .back-btn {
            margin-top: 20px;
            text-align: center;
        }
        .back-btn a {
            display: inline-block;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .back-btn a:hover {
            background: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tentang Aplikasi</h1>
        <p><strong>Nama Aplikasi:</strong> Trackify</p>
        <p><strong>Versi:</strong> 1.0.0</p>
        <p><strong>Deskripsi:</strong> Trackify adalah aplikasi pelaporan kerusakan jalan yang dirancang untuk memudahkan pengguna melaporkan dan memantau kondisi jalan secara efisien. Aplikasi ini juga menyediakan visualisasi data laporan yang membantu pengambilan keputusan cepat dan akurat.</p>
        <p><strong>Pengembang:</strong> Kelompok 11</p>
    </div>

    <div class="back-btn">
        <a href="index.php">Kembali</a>
    </div>
</body>
</html>
