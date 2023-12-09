<?php
// Importovat třídu DbConnection
use Database\DbConnection;

// Importovat třídu DbConnection
require_once(__DIR__.'/../../Database/DbConnection.php');

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
    $profilePicType = pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION);
    
    // Convert the image to JPEG format with quality 90
    $convertedProfilePic = imagecreatefromstring(file_get_contents($profilePicTmp));
    ob_start();
    imagejpeg($convertedProfilePic, NULL, 90);
    $profilePic = ob_get_contents();
    ob_end_clean();

    // Vložení dat do databáze
    $sql = "INSERT INTO users (firstName, lastName, email, phone, gender, profilePic, username, password)
            VALUES ('$firstName', '$lastName', '$email', '$phone', '$gender', ?, '$username', '$password')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $profilePic);
    
    if ($stmt->execute()) {
        echo "Registrace úspěšná";
    } else {
        echo "Chyba při registraci: " . $conn->error;
    }

    // Uzavření spojení s databází
    $dbConnection->closeConnection();
}
?>
