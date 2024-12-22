from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/chatbot', methods=['POST'])
def chatbot():
    user_message = request.json.get('message', '').lower()

    responses = {
        'halo': 'Halo! Ada yang bisa kami bantu terkait aplikasi Trackify?',
        'masalah aplikasi': 'Bisa dijelaskan lebih detail masalah yang Anda alami? Misalnya: error login atau fitur tidak berfungsi.',
        'cara update': 'Untuk update aplikasi, buka Play Store atau App Store, cari Trackify, lalu pilih "Update".',
        'terima kasih': 'Sama-sama! Jika ada hal lain yang bisa kami bantu, jangan ragu untuk menghubungi kami.',
        'kerusakan jalan': 'Untuk melaporkan kerusakan jalan, gunakan aplikasi Trackify dan pilih opsi "Laporan Kerusakan Jalan".',
        'cuaca': 'Anda dapat melihat informasi cuaca terkini melalui aplikasi Trackify pada fitur cuaca di bagian navigasi.',
        'lokasi': 'Trackify dapat memberikan informasi terkait lokasi dan kondisi jalan berdasarkan data yang dilaporkan pengguna.',
        'pemeliharaan': 'Tim kami sedang bekerja untuk memastikan pemeliharaan jalan yang optimal. Anda dapat melaporkan kendala yang Anda alami melalui Trackify.',
        'tentang': 'Trackify adalah aplikasi untuk melaporkan dan memantau kerusakan jalan secara real-time serta memberikan data visual yang bermanfaat.',
        'fitur': 'Trackify menyediakan fitur seperti laporan kerusakan, peta jalan, informasi cuaca, dan statistik laporan pengguna.',
    }

    default_response = 'Maaf, saya kurang memahami pertanyaan Anda. Bisa dijelaskan lebih lanjut?'

    response = default_response
    for keyword in responses:
        if keyword in user_message:
            response = responses[keyword]
            break

    return jsonify({'response': response})

if __name__ == '__main__':
    app.run(debug=True)
