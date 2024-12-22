<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cuaca dan Prediksi - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
            color: #333;
            box-sizing: border-box;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .card h3 {
            font-size: 24px;
            margin-top: 0;
            color: #007BFF;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }

        .table th {
            background-color: #007BFF;
            color: white;
        }

        .table td {
            background-color: #f9f9f9;
        }

        .table td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ddd;
        }

        .back-btn {
            margin-top: 20px;
            text-align: center;
            margin-bottom:20px;
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

        @media (max-width: 768px) {
            header {
                font-size: 24px;
            }

            .table, .table th, .table td {
                font-size: 14px;
            }

            .card {
                padding: 15px;
            }
        }

    </style>
</head>
<body> 
    <main>
        <div class="card">
            <h3>Cuaca Saat Ini</h3>
            <table class="table">
                <tr>
                    <th>Lokasi</th>
                    <th>Suhu</th>
                    <th>Deskripsi</th>
                    <th>Ikon</th>
                </tr>
                <tr>
                    <td id="weather-location">Memuat...</td>
                    <td id="weather-temp">Memuat...</td>
                    <td id="weather-desc">Memuat...</td>
                    <td><img id="weather-icon" src="" alt="Weather Icon"></td>
                </tr>
            </table>
        </div>

        <div class="card">
            <h3>Prediksi Cuaca 5 Hari</h3>
            <table class="table">
                <tr>
                    <th>Tanggal</th>
                    <th>Ikon</th>
                    <th>Suhu</th>
                    <th>Deskripsi</th>
                </tr>
                <tbody id="forecast-table">
                </tbody>
            </table>
        </div>

        <div class="back-btn">
            <a href="index.php">Kembali</a>
        </div>
    </main>

    <script>
        const API_KEY = '1f80eb68b2910334aade16acb1d517fa';

        async function fetchWeather(lat, lon) {
            try {
                const currentWeatherResponse = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric&lang=id`);
                const forecastResponse = await fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${API_KEY}&units=metric&lang=id`);

                const currentWeatherData = await currentWeatherResponse.json();
                const forecastData = await forecastResponse.json();

                displayCurrentWeather(currentWeatherData);
                displayForecast(forecastData);
            } catch (error) {
                console.error('Error fetching weather data:', error);
                document.getElementById('weather-text').innerText = 'Gagal memuat data cuaca.';
            }
        }

        function displayCurrentWeather(data) {
            const icon = `https://openweathermap.org/img/wn/${data.weather[0].icon}.png`;
            document.getElementById('weather-icon').src = icon;
            document.getElementById('weather-location').innerText = `${data.name}`;
            document.getElementById('weather-temp').innerText = `${data.main.temp}°C`;
            document.getElementById('weather-desc').innerText = data.weather[0].description;
        }

        function displayForecast(data) {
            const forecastTable = document.getElementById('forecast-table');
            forecastTable.innerHTML = '';

            const forecastList = data.list.filter((_, index) => index % 8 === 0); 

            forecastList.forEach(item => {
                const date = new Date(item.dt_txt).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' });
                const icon = `https://openweathermap.org/img/wn/${item.weather[0].icon}.png`;
                const temp = `${item.main.temp}°C`;
                const description = item.weather[0].description;

                const forecastRow = document.createElement('tr');
                forecastRow.innerHTML = `
                    <td>${date}</td>
                    <td><img src="${icon}" alt="${description}"></td>
                    <td>${temp}</td>
                    <td>${description}</td>
                `;
                forecastTable.appendChild(forecastRow);
            });
        }

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => fetchWeather(position.coords.latitude, position.coords.longitude),
                    () => alert('Gagal mendapatkan lokasi.')
                );
            } else {
                alert('Geolocation tidak didukung di browser ini.');
            }
        }

        window.onload = getUserLocation;
    </script>
</body>
</html>
