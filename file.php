<?php
class AESCrypt {
    private $key;

    public function __construct($key) {
        $this->key = $key;
    }

    public function encryptFile($inputFile, $outputFile) {
        $data = file_get_contents($inputFile);
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_size);
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->key, 0, $iv);
        $encryptedData = base64_encode($iv . $encrypted);
        file_put_contents($outputFile, $encryptedData);
    }

    public function decryptFile($inputFile, $outputFile) {
        $encryptedData = file_get_contents($inputFile);
        $encryptedData = base64_decode($encryptedData);
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encryptedData, 0, $iv_size);
        $data = openssl_decrypt(substr($encryptedData, $iv_size), 'aes-256-cbc', $this->key, 0, $iv);
        file_put_contents($outputFile, $data);
    }
}

$key = "SecretKey123"; // Ganti dengan kunci rahasia Anda
$crypt = new AESCrypt($key);

if(isset($_POST["encryptFile"])) {
    $inputFile = $_FILES["file"]["tmp_name"];
    $outputFile = "encrypted_file.txt"; // Nama file hasil enkripsi
    $crypt->encryptFile($inputFile, $outputFile);
    $result = "File berhasil dienkripsi.";
} elseif(isset($_POST["decryptFile"])) {
    $inputFile = $_FILES["file"]["tmp_name"];
    $outputFile = "decrypted_file.txt"; // Nama file hasil dekripsi
    $crypt->decryptFile($inputFile, $outputFile);
    $result = "File berhasil didekripsi.";
} else {
    $result = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style2.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <style>
         .result {
            color: #767676; /* Ganti dengan warna yang Anda inginkan */
        }
        .inputFile {
            color: blue;
        }
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
        }
        .for {
            color: white;
        }
    </style>
    <title>AES File Encryption Decryption</title>
</head>
<body>
    <h1>AES Encryption Decryption File</h1>
    <div style="background-color: #000000;" class="container">
        <form method="post" enctype="multipart/form-data">
            <div>
                <label style="color: #767676;" for="file">Pilih File:</label>
                <input type="file" id="file" name="file" style="color: #767676;">
            </div>
            <div class="form-group">
                <input type="submit" name="encryptFile" value="Encrypt File">
                <input type="submit" name="decryptFile" value="Decrypt File">
            </div>
        </form>
        <div class="result">
            <?php echo $result; ?>
        </div>
    </div>
    <div class="footer">
        <form class="login-form" action="home.php" method="post">
            <button style="width: 200px; height: 40px; font-size: 12px;" class="btn btn-primary btn-block" name="login">Home <i class="fa fa-sign-in fa-lg"></i></button><br>
        </form>
    </div>
</body>
</html>
