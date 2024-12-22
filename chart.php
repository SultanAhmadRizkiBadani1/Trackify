<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackify";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sqlKategori = "SELECT kategori, COUNT(*) AS jumlah FROM laporan_kerusakan GROUP BY kategori";
$resultKategori = $conn->query($sqlKategori);
$kategoriLabels = [];
$kategoriData = [];

if ($resultKategori->num_rows > 0) {
    while ($row = $resultKategori->fetch_assoc()) {
        $kategoriLabels[] = $row['kategori'];
        $kategoriData[] = $row['jumlah'];
    }
}

$sqlStatus = "SELECT status, COUNT(*) AS jumlah FROM laporan_kerusakan GROUP BY status";
$resultStatus = $conn->query($sqlStatus);
$statusLabels = [];
$statusData = [];

if ($resultStatus->num_rows > 0) {
    while ($row = $resultStatus->fetch_assoc()) {
        $statusLabels[] = $row['status'];
        $statusData[] = $row['jumlah'];
    }
}

$sqlTanggal = "SELECT DATE(tanggal) AS tanggal, COUNT(*) AS jumlah FROM laporan_kerusakan GROUP BY DATE(tanggal)";
$resultTanggal = $conn->query($sqlTanggal);
$tanggalLabels = [];
$tanggalData = [];

if ($resultTanggal->num_rows > 0) {
    while ($row = $resultTanggal->fetch_assoc()) {
        $tanggalLabels[] = $row['tanggal'];
        $tanggalData[] = $row['jumlah'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisasi Data - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 90%;
            max-width: 700px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }

        h2 {
            margin-bottom: 20px;
            color: #555;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eef2f3;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #007BFF;
        }

        #chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 90%;
            max-width: 700px;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 100% !important;
            height: auto !important;
            max-height: 300px;
        }

        .chart-navigation {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        button {
            padding: 10px 15px;
            font-size: 1em;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background-color: #0b7dda;
            transform: scale(1.05);
        }

        .back-button {
            display: block;
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            margin-top: 30px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #1e88e5;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }

            button {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

    <h1>Visualisasi Data Trackify</h1>

    <div id="chart-container">

        <div class="chart-container" id="barChartContainer">
            <h2>Bar Chart: Jumlah Laporan per Kategori</h2>
            <canvas id="barChart"></canvas>
        </div>

        <div class="chart-container" id="pieChartContainer">
            <h2>Pie Chart: Status Laporan</h2>
            <canvas id="pieChart"></canvas>
        </div>

        <div class="chart-container" id="lineChartContainer">
            <h2>Line Chart: Laporan per Tanggal</h2>
            <canvas id="lineChart"></canvas>
        </div>

        <div class="chart-container" id="areaChartContainer">
            <h2>Area Chart: Laporan per Tanggal</h2>
            <canvas id="areaChart"></canvas>
        </div>

        <div class="chart-container" id="donutChartContainer">
            <h2>Donut Chart: Persentase Status</h2>
            <canvas id="donutChart"></canvas>
        </div>

        <div class="chart-container" id="radarChartContainer">
            <h2>Radar Chart: Analisis Kategori</h2>
            <canvas id="radarChart"></canvas>
        </div>

        <div class="chart-container" id="polarChartContainer">
            <h2>Polar Area Chart: Perbandingan Kategori</h2>
            <canvas id="polarChart"></canvas>
        </div>

        <div class="chart-navigation">
            <button id="prevChart" onclick="prevChart()">Kembali</button>
            <button id="nextChart" onclick="nextChart()">Selanjutnya</button>
        </div>
    </div>

    <a href="index.php" class="back-button">Kembali</a>

    <script>
        const kategoriLabels = <?php echo json_encode($kategoriLabels); ?>;
        const kategoriData = <?php echo json_encode($kategoriData); ?>;

        const statusLabels = <?php echo json_encode($statusLabels); ?>;
        const statusData = <?php echo json_encode($statusData); ?>;

        const tanggalLabels = <?php echo json_encode($tanggalLabels); ?>;
        const tanggalData = <?php echo json_encode($tanggalData); ?>;

        const chartColors = [
            'rgba(255, 159, 64, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)',
            'rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(255, 206, 86, 0.6)'
        ];

        const chartContainers = [
            'barChartContainer', 'pieChartContainer', 'lineChartContainer',
            'areaChartContainer', 'donutChartContainer', 'radarChartContainer', 'polarChartContainer'
        ];

        const charts = [
            new Chart(document.getElementById('barChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: kategoriData,
                        backgroundColor: chartColors,
                        borderColor: chartColors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            }),

            new Chart(document.getElementById('pieChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: chartColors,
                        borderColor: chartColors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    }
                }
            }),

            new Chart(document.getElementById('lineChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: tanggalLabels,
                    datasets: [{
                        label: 'Jumlah Laporan per Tanggal',
                        data: tanggalData,
                        fill: false,
                        borderColor: chartColors[0],
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            }),

            new Chart(document.getElementById('areaChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: tanggalLabels,
                    datasets: [{
                        label: 'Jumlah Laporan per Tanggal',
                        data: tanggalData,
                        fill: true,
                        backgroundColor: chartColors[1],
                        borderColor: chartColors[1],
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            }),

            new Chart(document.getElementById('donutChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: chartColors,
                        borderColor: chartColors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    }
                }
            }),

            new Chart(document.getElementById('radarChart').getContext('2d'), {
                type: 'radar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Analisis Kategori',
                        data: kategoriData,
                        backgroundColor: chartColors[1],
                        borderColor: chartColors[1],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    }
                }
            }),

            new Chart(document.getElementById('polarChart').getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        data: kategoriData,
                        backgroundColor: chartColors,
                        borderColor: chartColors.map(color => color.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    }
                }
            })
        ];

        let currentChartIndex = 0;

        function showChart(index) {
            chartContainers.forEach((container, idx) => {
                document.getElementById(container).style.display = (idx === index) ? 'block' : 'none';
            });
        }

        function prevChart() {
            currentChartIndex = (currentChartIndex === 0) ? charts.length - 1 : currentChartIndex - 1;
            showChart(currentChartIndex);
        }

        function nextChart() {
            currentChartIndex = (currentChartIndex === charts.length - 1) ? 0 : currentChartIndex + 1;
            showChart(currentChartIndex);
        }

        showChart(currentChartIndex);
    </script>
</body>
</html>