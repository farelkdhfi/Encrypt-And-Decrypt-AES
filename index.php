<?php
class AESCrypt {
    private $key;

    public function __construct($key) {
        $this->key = $key;
    }

    public function encrypt($data) {
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_size);
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($encryptedData) {
        $encryptedData = base64_decode($encryptedData);
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encryptedData, 0, $iv_size);
        $data = openssl_decrypt(substr($encryptedData, $iv_size), 'aes-256-cbc', $this->key, 0, $iv);
        return $data;
    }
}

$key = "SecretKey123"; // kunci rahasia
$crypt = new AESCrypt($key);

$data = $_POST["data"] ?? "";
$result = "";

if(isset($_POST["encrypt"])) {
    $encryptedData = $crypt->encrypt($data);
    $result = "Data berhasil dienkripsi: " . $encryptedData;
} elseif(isset($_POST["decrypt"])) {
    $decryptedData = $crypt->decrypt($data);
    $result = "Data berhasil didekripsi: " . $decryptedData;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #101010;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #101010;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #000000;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #000;
        }
        .result {
            color: #767676; /* Ganti dengan warna yang Anda inginkan */
        }
        .for {
            color: white;
        }
        .img {
            width: 1;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AES Encryption Decryption</title>
</head>
<body>
<img src="img/loremmp.png" width="50" height="50">

    <h1>AES Encryption Decryption Text</h1>
    <div style="background-color: #000000;" class="container">
        <form method="post">
            <div class="form-group">
            <label style="color: #767676; font-size: 10px;" for="data">Write anything:</label>
                <input style="color: #555555;"type="text" id="data" name="data" value="<?php echo htmlspecialchars($data); ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="encrypt" value="Encrypt">
                <input type="submit" name="decrypt" value="Decrypt">
            </div>
        </form>
        <div class="result">
            <h2 style="font-size: 12px;" >Hasil:</h2>
            <?php echo $result; ?>
        </div>
    </div>
    <div class="footer">
    <form class="login-form" action="file.php" method="post">
    <button style="width: 200px; height: 40px; font-size: 12px;" class="btn btn-primary btn-block" name="login">encrypt & decrypt file <i class="fa fa-sign-in fa-lg"></i></button><br>
    </form>
    </div>
</body>
</html>
