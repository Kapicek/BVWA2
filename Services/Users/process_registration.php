<?php
// Importovat třídu DbConnection
use Database\DbConnection;

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
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Bezpečné ukládání hesla

    // Zpracování profilové fotografie
    $profilePicTmp = $_FILES['profilePic']['tmp_name'];

    // Příprava nových rozměrů (maximální šířka a výška)
    $maxWidth = 800;
    $maxHeight = 800;  // Můžete použít různé hodnoty pro šířku a výšku podle potřeby

    list($width, $height, $type) = getimagesize($profilePicTmp);



    $aspectRatio = $width / $height;

    if ($width > $height) {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $aspectRatio;
    } else {
        $newHeight = $maxHeight;
        $newWidth = $maxHeight * $aspectRatio;
    }

    // Vytvoření prázdného obrázku s novými rozměry
    $convertedProfilePic = imagecreatetruecolor($newWidth, $newHeight);

    // Nahrání původního obrázku
    $sourceImage = imagecreatefromjpeg($profilePicTmp);

    // Vytvoření změněného obrázku s novými rozměry
    imagecopyresampled($convertedProfilePic, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Konverze obrázku do formátu JPEG s kvalitou 90
    ob_start();
    imagejpeg($convertedProfilePic, NULL, 90);
    $profilePic = ob_get_contents();
    ob_end_clean();

    // Uzavření zdrojového a konvertovaného obrázku
    imagedestroy($sourceImage);
    imagedestroy($convertedProfilePic);


    $sql = "INSERT INTO users (firstName, lastName, email, phone, gender, profilePic, username, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Kontrola, zda došlo k chybě při přípravě dotazu
    if ($stmt === false) {
        die('Chyba při přípravě dotazu: ' . $conn->error);
    }

    // Parametry pro bind_param
    $stmt->bind_param('ssssssss', $firstName, $lastName, $email, $phone, $gender, $profilePic, $username, $password);

    // Odeslání binárních dat (pro případné obrázky apod.)
    $stmt->send_long_data(5, $profilePic);

    // Provedení dotazu
    $result = $stmt->execute();

    // Kontrola, zda došlo k chybě při provedení dotazu
    if ($result === false) {
        die('Chyba při provedení dotazu: ' . $stmt->error);
    }


    if ($stmt) {
        echo "Registrace úspěšná";
    } else {
        echo "Chyba při registraci: " . $conn->error;
    }

    // Uzavření spojení s databází
    $dbConnection->closeConnection();
}
?>