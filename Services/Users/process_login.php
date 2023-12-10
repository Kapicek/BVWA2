<?php

use Database\DbConnection;

session_start(); // Spuštění relace (session)

// Importovat třídu DbConnection
require_once(__DIR__.'/../../Database/DbConnection.php');

// Vytvořit instanci třídy pro připojení k databázi
$dbConnection = new DbConnection();
$conn = $dbConnection->getConnection();

// Zkontrolujeme, zda formulář byl odeslán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání dat z přihlašovacího formuláře
    $loginUsername = $_POST['loginUsername'];
    $loginPassword = $_POST['loginPassword'];

    // Příprava připraveného dotazu
    $sql = "SELECT * FROM users WHERE username = ?";
    
    // Příprava a provedení připraveného dotazu
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $loginUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Uživatel nalezen
        $row = $result->fetch_assoc();
        if (password_verify($loginPassword, $row['password'])) {
            // Heslo je platné
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $isAdmin = $row['permission'];

            // Pokud je přihlášený uživatel admin, přesměruje ho
            if($isAdmin == 1) {
                header('Location: http://localhost/bvwa2/view/userprofile.php'); //FIXME fixnout tohle Kubo <3
            } else {
                header('Location: http://localhost/bvwa2/view/userprofile.php');
            }

            exit(); // Ukončí běh skriptu po přesměrování
        } else {
            echo "Nesprávné heslo";
        }
        
    } else {
        echo "Uživatel nenalezen";
    }

    // Uzavření připraveného dotazu
    $stmt->close();
}

// Uzavření spojení s databází
$dbConnection->closeConnection();
?>
