<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackify";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT id, deskripsi, lokasi, kategori, status, tanggal FROM laporan_kerusakan ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Laporan - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }
        thead th {
            background-color: #007BFF;
            color: white;
            text-align: left;
            padding: 12px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        td, th {
            padding: 12px;
            border: 1px solid #ddd;
        }
        td.center, th.center {
            text-align: center;
        }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            tr {
                margin-bottom: 10px;
            }
            td {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
            }
            td::before {
                content: attr(data-label);
                flex-basis: 50%;
                font-weight: bold;
            }
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
    <h2>Data Laporan Kerusakan</h2>
    <table>
        <thead>
        <tr>
            <th class="center">ID</th>
            <th>Deskripsi</th>
            <th>Lokasi</th>
            <th class="center">Kategori</th>
            <th class="center">Status</th>
            <th class="center">Tanggal</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID" class="center"><?php echo $row['id']; ?></td>
                    <td data-label="Deskripsi"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                    <td data-label="Lokasi"><?php echo htmlspecialchars($row['lokasi']); ?></td>
                    <td data-label="Kategori" class="center"><?php echo htmlspecialchars($row['kategori']); ?></td>
                    <td data-label="Status" class="center"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td data-label="Tanggal" class="center"><?php echo htmlspecialchars($row['tanggal']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="center">Tidak ada laporan</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="back-btn">
        <a href="index.php">Kembali</a>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
