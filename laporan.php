<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackify";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['laporan'])) {
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $lokasi = $conn->real_escape_string($_POST['lokasi']);
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "INSERT INTO laporan_kerusakan (deskripsi, lokasi, kategori, status) 
            VALUES ('$deskripsi', '$lokasi', '$kategori', '$status')";  
    if ($conn->query($sql) === TRUE) {
        $feedback = "Laporan berhasil ditambahkan!";
        $feedback_class = "success";
    } else {
        $feedback = "Error: " . $conn->error;
        $feedback_class = "error";
    }
}

$sql = "SELECT * FROM laporan_kerusakan ORDER BY tanggal DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan Jalan - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
            color: #007BFF;
        }
        form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        form textarea, form input{
            width: 97.5%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        button {
            background: #007BFF;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #0b7dda;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #f8f8f8;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .feedback {
            text-align: center;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
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
    <h2>Laporan Kerusakan Jalan</h2>
    <?php if (isset($feedback)): ?>
        <div class="feedback <?php echo $feedback_class; ?>">
            <?php echo $feedback; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <textarea name="deskripsi" placeholder="Deskripsi kerusakan jalan" required></textarea>
        <input type="text" name="lokasi" placeholder="Lokasi kerusakan" required>
        <select name="kategori" required>
            <option value="">Pilih Kategori</option>
            <option value="Lubang">Lubang</option>
            <option value="Paving Rusak">Paving Rusak</option>
            <option value="Jalan Tergenang Air">Jalan Tergenang Air</option>
            <option value="Rambu Hilang">Rambu Hilang</option>
        </select>
        <select name="status" required>
            <option value="">Pilih Status</option>
            <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
            <option value="Dalam Proses Perbaikan">Dalam Proses Perbaikan</option>
            <option value="Selesai">Selesai</option>
        </select>
        <button type="submit" name="laporan">Kirim Laporan</button>
    </form>
    <div class="back-btn">
        <a href="index.php">Kembali</a>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
