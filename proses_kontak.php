<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: kontak.php");
    exit;
}

$nama   = htmlspecialchars(trim($_POST["nama"] ?? ""));
$email  = htmlspecialchars(trim($_POST["email"] ?? ""));
$subjek = htmlspecialchars(trim($_POST["subjek"] ?? ""));
$pesan  = htmlspecialchars(trim($_POST["pesan"] ?? ""));


if (
    empty($nama) ||
    empty($email) ||
    empty($subjek) ||
    empty($pesan)
) {

    echo "
        <script>
            alert('Semua field harus diisi!');
            window.location.href='kontak.php';
        </script>
    ";

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "
        <script>
            alert('Format email tidak valid!');
            window.location.href='kontak.php';
        </script>
    ";

    exit;
}


/*
|--------------------------------------------------------------------------
| Untuk sementara pesan ditampilkan.
| Bisa diganti menggunakan PHPMailer agar masuk ke email.
|--------------------------------------------------------------------------
*/

echo "
<!DOCTYPE html>
<html lang='id'>
<head>

<meta charset='UTF-8'>

<title>Pesan Terkirim</title>

<style>

body {
    background:#000;
    color:white;
    font-family:Arial,sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
}

.box {
    width:90%;
    max-width:500px;
    padding:40px;
    background:#151518;
    border:1px solid #29292d;
    border-radius:20px;
    text-align:center;
}

h1 {
    color:#ffbf00;
}

a {
    display:inline-block;
    margin-top:20px;
    padding:12px 20px;
    background:#ffbf00;
    color:#000;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
}

</style>

</head>

<body>

<div class='box'>

<h1>Pesan Terkirim!</h1>

<p>
Terima kasih <strong>$nama</strong>.
</p>

<p>
Pesan Anda berhasil diterima.
</p>

<a href='kontak.php'>
Kembali
</a>

</div>

</body>
</html>
";

?>