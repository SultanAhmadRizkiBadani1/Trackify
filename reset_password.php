<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackify";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT * FROM password_resets WHERE token = '$token' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($new_password) || empty($confirm_password)) {
                $error_message = "Password baru tidak boleh kosong.";
            } elseif ($new_password != $confirm_password) {
                $error_message = "Password dan konfirmasi password tidak cocok.";
            } else {
                $row = $result->fetch_assoc();
                $email = $row['email'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
                if ($conn->query($update_sql) === TRUE) {
                    $delete_sql = "DELETE FROM password_resets WHERE token = '$token'";
                    $conn->query($delete_sql);

                    $success_message = "Password Anda telah berhasil diubah. Silakan login.";
                } else {
                    $error_message = "Terjadi kesalahan saat mengubah password.";
                }
            }
        }
    } else {
        $error_message = "Token tidak valid atau telah kedaluwarsa.";
    }
} else {
    $error_message = "Token tidak ditemukan.";
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
        <title>Reset Password - Trackify</title>
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
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Reset Kata Sandi</h3></div>
                                    <div class="card-body">
                                        <?php if ($error_message != "") echo "<p style='color:red;'>$error_message</p>"; ?>
                                        <?php if ($success_message != "") echo "<p style='color:green;'>$success_message</p>"; ?>
                                        <form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="newPassword" type="password" name="new_password" placeholder="Kata Sandi Baru" required />
                                                <label for="newPassword">Kata Sandi Baru</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="confirmPassword" type="password" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required />
                                                <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="login.php">Kembali ke login</a>
                                                <button class="btn btn-primary" type="submit">Reset Kata Sandi</button>
                                            </div>
                                        </form>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
