<?php
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $apiKey = "1f80eb68b2910334aade16acb1d517fa";
    $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
    $weatherData = file_get_contents($url);
    
    if ($weatherData === false) {
        echo json_encode(['error' => 'Gagal memuat data cuaca']);
    } else {
        echo $weatherData;
    }
} else {
    echo json_encode(['error' => 'Parameter lat dan lon tidak ditemukan.']);
}
?>
