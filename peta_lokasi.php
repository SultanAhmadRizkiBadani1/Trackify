<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Lokasi Pengguna dan Cuaca - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
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

        h2 {
            font-size: 2.5em;
            color: #007BFF;
            text-align: center;
            margin-bottom: 20px;
        }

        #map {
            width: 90%;
            max-width: 900px;
            height: 500px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .leaflet-popup-content {
            font-size: 1.1em;
            color: #333;
            line-height: 1.6;
        }

        .leaflet-popup-content b {
            color: #007BFF;
        }

        .weather-info {
            margin-top: 15px;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .weather-info p {
            margin: 5px 0;
        }

        .weather-info h4 {
            margin-top: 10px;
            font-size: 1.2em;
            color: #007BFF;
        }

        .back-button {
            display: block;
            text-align: center;
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #1e88e5;
        }

    </style>
</head>
<body>

    <h2>Peta Lokasi Pengguna dan Cuaca</h2>
    <div id="map"></div>
    <a href="index.php" class="back-button">Kembali</a>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLon = position.coords.longitude;

                var map = L.map('map').setView([userLat, userLon], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                }).addTo(map);

                var userMarker = L.marker([userLat, userLon]).addTo(map)
                    .bindPopup("<b>Lokasi Anda</b><br>Lat: " + userLat + "<br>Lon: " + userLon)
                    .openPopup();

                var apiKey = '1f80eb68b2910334aade16acb1d517fa';
                var weatherUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${userLat}&lon=${userLon}&appid=${apiKey}&units=metric&lang=id`;

                fetch(weatherUrl)
                    .then(response => response.json())
                    .then(data => {
                        var weatherDescription = data.weather[0].description;
                        var temperature = data.main.temp;
                        var humidity = data.main.humidity;
                        var cityName = data.name;

                        var weatherInfo = `
                            <div class="weather-info">
                                <h4>Cuaca di ${cityName}</h4>
                                <p><b>Deskripsi Cuaca:</b> ${weatherDescription}</p>
                                <p><b>Suhu:</b> ${temperature}°C</p>
                                <p><b>Kelembaban:</b> ${humidity}%</p>
                            </div>
                        `;

                        userMarker.bindPopup(`
                            <b>Lokasi Anda</b><br>
                            Lat: ${userLat}<br>
                            Lon: ${userLon}<br>
                            <br>
                            ${weatherInfo}
                        `).openPopup();
                    })
                    .catch(error => {
                        console.error("Error fetching weather data: ", error);
                        userMarker.bindPopup("<b>Cuaca tidak tersedia</b>").openPopup();
                    });
            }, function() {
                alert("Geolokasi gagal diakses.");
            });
        } else {
            alert("Geolokasi tidak didukung oleh browser Anda.");
        }
    </script>

</body>
</html>
