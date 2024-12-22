<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="Silapor.jpg">
    <title>Chatbot - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
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

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #007BFF;
        }

        #chatbox {
            border: 1px solid #ddd;
            padding: 15px;
            width: 90%;
            max-width: 700px;
            height: 450px;
            margin-bottom: 20px;
            background-color: #ffffff;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: fadeIn 0.5s ease;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message {
            margin-bottom: 12px;
            padding: 12px 18px;
            border-radius: 15px;
            max-width: 80%;
            display: inline-block;
            position: relative;
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .user-message {
            background-color: #b3e5fc;
            color: #333;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
            margin-right: 8px;
            position: relative;
            max-width: 75%;
        }

        .user-message::after {
            content: "";
            position: absolute;
            right: -12px;
            top: 10px;
            border: 8px solid transparent;
            border-left-color: #b3e5fc;
        }

        .bot-message {
            background-color: #c8e6c9;
            color: #333;
            align-self: flex-start;
            margin-right: auto;
            margin-left: 8px;
            position: relative;
            max-width: 75%;
        }

        .bot-message::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 10px;
            border: 8px solid transparent;
            border-right-color: #c8e6c9;
        }

        #user-input-container {
            width: 90%;
            max-width: 700px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        input[type="text"] {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1.1em;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #007BFF;
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
</head>
<body>

    <h1>Chatbot Jalan Rusak</h1>

    <div id="chatbox"></div>

    <div id="user-input-container">
        <input type="text" id="user-input" placeholder="Tanya sesuatu..." onkeydown="checkEnter(event)"/>
        <button onclick="sendMessage()">Kirim</button>
    </div>

    <a href="index.php" class="back-button">Kembali</a>

    <script>
        const chatbox = document.getElementById("chatbox");

        function checkEnter(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        }

        function sendMessage() {
            const userInput = document.getElementById("user-input").value;

            if (userInput.trim() === "") {
                return;
            }

            addMessage("Anda", userInput, "user-message");

            addTypingIndicator();

            fetch('chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: userInput })
            })
                .then(response => response.json())
                .then(data => {
                    removeTypingIndicator();
                    addMessage("Chatbot", data.reply, "bot-message");
                })
                .catch(error => {
                    removeTypingIndicator();
                    addMessage("Chatbot", "Terjadi kesalahan. Coba lagi nanti.", "bot-message");
                    console.error('Error:', error);
                });

            document.getElementById("user-input").value = "";
        }

        function addMessage(sender, text, className) {
            const messageElement = document.createElement("div");
            messageElement.classList.add("message", className);
            messageElement.innerHTML = `<strong>${sender}:</strong> ${text}`;
            chatbox.appendChild(messageElement);
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function addTypingIndicator() {
            const typingIndicator = document.createElement("div");
            typingIndicator.id = "typing-indicator";
            typingIndicator.classList.add("typing-indicator");
            typingIndicator.textContent = "Chatbot sedang mengetik...";
            chatbox.appendChild(typingIndicator);
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function removeTypingIndicator() {
            const typingIndicator = document.getElementById("typing-indicator");
            if (typingIndicator) {
                chatbox.removeChild(typingIndicator);
            }
        }
    </script>

</body>
</html>
