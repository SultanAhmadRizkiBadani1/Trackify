from flask import Flask, request, jsonify
import mysql.connector
import requests
import re

app = Flask(__name__)

db = mysql.connector.connect(
    host="localhost",
    user="root", 
    password="",    
    database="Trackify"
)

WEATHER_API_KEY = "1f80eb68b2910334aade16acb1d517fa"

@app.route('/chatbot', methods=['POST'])
def chatbot():
    user_message = request.json.get('message', '').lower().strip()

    if "jalan rusak" in user_message or "jalan berlubang" in user_message:
        cursor = db.cursor()
        cursor.execute("SELECT lokasi, deskripsi, status, kategori FROM laporan_kerusakan ORDER BY tanggal DESC LIMIT 5")  # Menampilkan 5 laporan terbaru
        results = cursor.fetchall()
        if results:
            response = "Daftar jalan rusak:\n"
            for lokasi, deskripsi, status, kategori in results:
                response += f"- Lokasi: {lokasi}, Deskripsi: {deskripsi}, Status: {status}, Kategori: {kategori}\n"
        else:
            response = "Saat ini tidak ada laporan jalan rusak. Silakan cek kembali nanti."
        return jsonify({'reply': response})

    elif "cuaca" in user_message:
        location = re.sub(r"cuaca\s*di", "", user_message).strip()
        if not location:
            return jsonify({'reply': "Silakan sebutkan lokasi yang ingin Anda ketahui cuacanya (contoh: 'Cuaca di Jakarta')."})
        
        weather_url = f"http://api.openweathermap.org/data/2.5/weather?q={location}&appid={WEATHER_API_KEY}&units=metric"
        weather_response = requests.get(weather_url).json()
        
        if weather_response.get("cod") != 200:
            return jsonify({'reply': f"Lokasi '{location}' tidak ditemukan. Pastikan nama kota sudah benar."})
        
        temp = weather_response['main']['temp']
        description = weather_response['weather'][0]['description']
        response = f"Perkiraan cuaca di {location}: {temp}°C, {description}."
        return jsonify({'reply': response})

    else:
        response = (
            "Saya tidak mengerti pertanyaan Anda. Coba tanyakan tentang 'jalan rusak' atau 'cuaca'.\n"
            "Misalnya: 'Jalan rusak di mana saja?' atau 'Cuaca di Jakarta'."
        )
        return jsonify({'reply': response})

@app.route('/laporan', methods=['POST'])
def add_laporan():
    data = request.json
    deskripsi = data.get('deskripsi')
    lokasi = data.get('lokasi')
    status = data.get('status', 'Belum Ditangani')
    kategori = data.get('kategori')

    if not deskripsi or not lokasi or not kategori:
        return jsonify({'reply': 'Deskripsi, lokasi, dan kategori harus diisi!'})

    cursor = db.cursor()
    cursor.execute(
        "INSERT INTO laporan (deskripsi, lokasi, status, kategori) VALUES (%s, %s, %s, %s)",
        (deskripsi, lokasi, status, kategori)
    )
    db.commit()

    return jsonify({'reply': 'Laporan jalan rusak berhasil ditambahkan!'})

@app.route('/laporan', methods=['GET'])
def get_laporan():
    cursor = db.cursor()
    cursor.execute("SELECT id_utama, deskripsi, lokasi, status, kategori, tanggal FROM laporan ORDER BY tanggal DESC LIMIT 5")
    results = cursor.fetchall()
    laporan_list = []
    for row in results:
        laporan_list.append({
            'id': row[0],
            'deskripsi': row[1],
            'lokasi': row[2],
            'status': row[3],
            'kategori': row[4],
            'tanggal': row[5]
        })
    
    return jsonify(laporan_list)

if __name__ == '__main__':
    app.run(debug=True)
