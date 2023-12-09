<?php
// Importovat třídu DbConnection
use Database\DbConnection;

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

    // Zpracování profilové fotografie (uložení do složky a uložení cesty do databáze)
    $target_dir = "uploads/";
    $profilePic = $target_dir . basename($_FILES["profilePic"]["name"]);
    move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePic);

    // Vložení dat do databáze
    $sql = "INSERT INTO users (firstName, lastName, email, phone, gender, profilePic, username, password)
            VALUES ('$firstName', '$lastName', '$email', '$phone', '$gender', '$profilePic', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registrace úspěšná";
    } else {
        echo "Chyba při registraci: " . $conn->error;
    }
}

// Uzavření spojení s databází
$dbConnection->closeConnection();
?>