<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Trackify";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (empty($email)) {
        $error_message = "Email tidak boleh kosong.";
    } else {
        $sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();
        
        if ($result->num_rows > 0) {
            $token = bin2hex(random_bytes(50));

            $sql_reset = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
            $sql_reset->bind_param("ss", $email, $token);
            if ($sql_reset->execute()) {
                $reset_link = "http://yourwebsite.com/reset_form.php?token=$token";
                $subject = "Reset Kata Sandi Anda";
                $message = "Klik tautan berikut untuk mengatur ulang kata sandi Anda: $reset_link";
                $headers = "From: no-reply@yourwebsite.com";

                if (mail($email, $subject, $message, $headers)) {
                    $success_message = "Kami telah mengirimkan link untuk reset kata sandi ke email Anda.";
                } else {
                    $error_message = "Terjadi kesalahan saat mengirim email.";
                }
            } else {
                $error_message = "Terjadi kesalahan. Coba lagi.";
            }
        } else {
            $error_message = "Email tidak ditemukan.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Pemulihan Password - Trackify</title>
    <link rel="shortcut icon" href="img/Trackify.png">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Pemulihan Kata Sandi</h3></div>
                                <div class="card-body">
                                    <div class="small mb-3 text-muted">Masukkan alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi Anda.</div>
                                    <form action="reset_password.php" method="POST">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" type="email" name="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Alamat Email</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="login.php">Kembali ke login</a>
                                            <button class="btn btn-primary" type="submit">Reset Kata Sandi</button>
                                        </div>
                                    </form>
                                    <?php if ($error_message != "") echo "<p style='color:red;'>$error_message</p>"; ?>
                                    <?php if ($success_message != "") echo "<p style='color:green;'>$success_message</p>"; ?>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="register.php">Butuh akun? Daftar sekarang!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutAuthentication_footer"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
