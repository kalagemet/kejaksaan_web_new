<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        p {
            color: #666;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verifikasi Akun</h2>
        <p>Selamat datang di Halo JPN! Untuk melanjutkan, verifikasikan akun Anda dengan mengklik tombol di bawah ini:</p>
        <a href="<?= $url ?>" class="button">Verifikasi Akun</a>
        <p>Jika Anda tidak merasa melakukan pendaftaran, Anda bisa mengabaikan email ini.</p>
        <p>Terima kasih!</p>
    </div>
</body>

</html>
