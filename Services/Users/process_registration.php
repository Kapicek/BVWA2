<?php
// Importovat třídu DbConnection
use Database\DbConnection;

session_start();

// Importovat třídu DbConnection
require_once(__DIR__ . '/../../Database/DbConnection.php');

// Vytvořit instanci třídy pro připojení k databázi
$dbConnection = new DbConnection();
$conn = $dbConnection->getConnection();

// Zpracování přihlášení...
// Zkontrolujeme, zda formulář byl odeslán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání dat z registračního formuláře
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Zpracování profilové fotografie
    $profilePicTmp = $_FILES['profilePic']['tmp_name'];

    // Příprava nových rozměrů (maximální šířka a výška)
    list($width, $height, $type) = getimagesize($profilePicTmp);

    $newWidth = 800;
    $newHeight = ($height / $width) * $newWidth;

    // Nahrání původního obrázku
    $sourceImage = imagecreatefromstring(file_get_contents($profilePicTmp));

    // Vytvoření změněného obrázku s novými rozměry
    $convertedProfilePic = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($convertedProfilePic, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Konverze obrázku do formátu JPEG s kvalitou 90
    ob_start();
    imagejpeg($convertedProfilePic, NULL, 90);
    $profilePic = ob_get_contents();
    ob_end_clean();

    // Uzavření zdrojového a konvertovaného obrázku
    imagedestroy($sourceImage);
    imagedestroy($convertedProfilePic);


    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $xss_reg = '/[<>]/';


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($username == $row["username"]) {
                echo '<script>alert("Tento username používá někdo jiný")</script>';
                echo '<script>window.location="../../index.php"</script>';
                die();
            }
        }
    }

    $heslo_reg = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()+=\-\[\]\';,.\/{}|":?~\\\\])[a-zA-Z0-9!@#$%^&*()+=\-\[\]\';,.\/{}|":?~\\\\]{8,}$/';

    if (preg_match($heslo_reg, $password)) {

    } else {
        echo '<script>alert("Heslo nesplňuje podmínky -> 8 znaků, alespoň jedno velké a jedno malé písmeno, alespoň jednu číslici a alespoň jeden speciální znak kromě<>")</script>';
        echo '<script>window.location="../../index.php"</script>';
        exit();
    }


    if ($password !== $password2) {
        echo '<script>alert("Hesla se neschodují")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();

    }

    preg_match($xss_reg, $firstName, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Jméno nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $lastName, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Příjmení nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $email, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Email nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Email nemá správný formát")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $phone, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Telefon nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }


    $phone_reg = '/^[0-9]{9}+$/';
    preg_match($phone_reg, $email, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Telefoní číslo nemá správný formát")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $gender, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("POhlaví nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    if ($gender == 'male' || $gender == 'female') {

    } else {

        echo '<script>alert("POhlaví není správně")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $username, $matches);
    if (count($matches) > 0) {
        echo '<script>alert("Uživatelské jméno nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    preg_match($xss_reg, $password, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Heslo nesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Bezpečné ukládání hesla

    try {
        $iv = bin2hex(random_bytes(16));
    } catch (\Random\RandomException $e) {
        echo '<script>alert("Někde je chyba prosím zkus to znovu")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    $iv_bin = hex2bin($iv);

    // Klíč pro šifrování a dešifrování
    $encryptionKey = "tajny_klic_pro_sifrovani";

    // Šifrování obsahu zprávy
    $encryptedEmail = openssl_encrypt($email, 'aes-256-cbc', $encryptionKey, 0, $iv_bin);

    $encryptedPhone = openssl_encrypt($phone, 'aes-256-cbc', $encryptionKey, 0, $iv_bin);





    $sql = "INSERT INTO users (firstName, lastName, email, phone, gender, profilePic, username, password, permission, key_iv)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Kontrola, zda došlo k chybě při přípravě dotazu
    if ($stmt === false) {
        echo '<script>alert("Nekde se stala chaba:  ' . $conn->error . '")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();

    }
    $zeno = 0;

    $stmt->bind_param("ssssssssis", $firstName, $lastName, $encryptedEmail, $encryptedPhone, $gender, $profilePic, $username, $password, $zeno, $iv_bin);

    // Odeslání binárních dat (pro případné obrázky apod.)
    $stmt->send_long_data(6, $profilePic);

    // Provedení dotazu

    $result = $stmt->execute();


    // Kontrola, zda došlo k chybě při provedení dotazu
    if ($result === false) {
        echo '<script>alert("Chyba při provedení dotazu:' . $stmt->error . '")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }


    if ($stmt) {

        $sql = "SELECT * FROM users WHERE username = ?";

        // Příprava a provedení připraveného dotazu
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Uživatel nalezen
            $row = $result->fetch_assoc();
            // Heslo je platné
            setcookie("user_id", $row["id"], time() + 1200, "/");
            setcookie("username", $row["username"], time() + 1200, "/");
            setcookie("perm", $row["permission"], time() + 1200, "/");

            // Pokud je přihlášený uživatel admin, přesměruje ho
            echo '<script>alert("Registrace úspěšná")</script>';
            echo '<script>window.location="../../View/UserProfile.php"</script>';
        }

    } else {
        echo '<script>alert("Registrace neúspěšná:' . $stmt->error . '")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

    // Uzavření spojení s databází
    $dbConnection->closeConnection();
}
?>