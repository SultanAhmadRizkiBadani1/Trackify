<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = trim($_POST['message']);

    if (empty($userMessage)) {
        echo json_encode(['error' => 'Pesan tidak boleh kosong.']);
        exit;
    }

    $ch = curl_init('http://localhost:5000/chatbot');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => strtolower($userMessage)]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['error' => 'Terjadi kesalahan saat menghubungi backend.']);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Respons dari server tidak valid.']);
        exit;
    }

    echo json_encode(['response' => htmlspecialchars($data['response'] ?? 'Maaf, terjadi kesalahan saat memproses permintaan Anda.')]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costumer Service - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7f8;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #333;
}

input[type="text"] {
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    width: 93.5%;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px;
    cursor: pointer;
    border-radius: 8px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

#response {
    margin-top: 20px;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    text-align: center;
}

.error {
    color: #dc3545;
    text-align: center;
}

button {
    padding: 12px 20px;
    font-size: 1.1em;
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

.typing-indicator {
    margin-top: 10px;
    font-style: italic;
    color: #777;
    font-size: 0.9em;
}

@media (max-width: 768px) {
    h1 {
        font-size: 2em;
    }

    input[type="text"], button {
        font-size: 1em;
        padding: 10px;
    }
}

</style>
<body>

    <div class="container">
        <h1>Costumer Service</h1>

        <form id="chatForm" method="POST">
            <label for="message">Masukkan pesan Anda:</label>
            <input type="text" id="message" name="message" required>
            <button type="submit">Kirim</button>
        </form>

        <h2>Respons Chatbot:</h2>
        <div id="response"></div>
        
        <a href="index.php" class="back-button">Kembali</a>
    </div>

    <script>
        const form = document.getElementById('chatForm');
        const responseDiv = document.getElementById('response');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const message = document.getElementById('message').value;

            try {
                const formData = new FormData();
                formData.append('message', message);

                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.error) {
                    responseDiv.innerHTML = `<p class="error">${result.error}</p>`;
                } else {
                    responseDiv.innerHTML = result.response;
                }
            } catch (error) {
                responseDiv.innerHTML = `<p class="error">Terjadi kesalahan: ${error.message}</p>`;
            }
        });
    </script>

</body>
</html>
