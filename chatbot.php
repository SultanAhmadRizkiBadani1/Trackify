<?php
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

$flask_url = 'http://127.0.0.1:5000/chatbot';

$data = json_encode(['message' => $message]);

$ch = curl_init($flask_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $response = json_encode(['reply' => 'Terjadi kesalahan. Coba lagi nanti.']);
}

curl_close($ch);

if (strpos(strtolower($message), 'cuaca') !== false) {
    preg_match('/cuaca di ([a-zA-Z\s]+)/', $message, $matches);
    
    if (isset($matches[1])) {
        $city = trim($matches[1]);
    } else {
        $city = 'Jakarta';
    }

    $apiKey = '1f80eb68b2910334aade16acb1d517fa';
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&lang=id";

    $weatherResponse = file_get_contents($apiUrl);
    $weatherData = json_decode($weatherResponse, true);

    if (isset($weatherData['weather'][0]['description']) && isset($weatherData['main']['temp'])) {
        $weatherDescription = $weatherData['weather'][0]['description'];
        $temperature = $weatherData['main']['temp'];
        $temperatureCelsius = $temperature - 273.15;

        $weatherReply = "Cuaca di $city saat ini: $weatherDescription dengan suhu sekitar " . round($temperatureCelsius) . "°C.";
    } else {
        $weatherReply = "Gagal mendapatkan informasi cuaca.";
    }

    $response = json_encode(['reply' => $weatherReply]);
}

echo $response;
?>
