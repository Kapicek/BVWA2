<?php

use Database\DbConnection;

session_start(); // Spuštění relace (session)

// Importovat třídu DbConnection
require_once(__DIR__ . '/../../Database/DbConnection.php');

// Vytvořit instanci třídy pro připojení k databázi
$dbConnection = new DbConnection();
$conn = $dbConnection->getConnection();

// Zkontrolujeme, zda formulář byl odeslán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání dat z přihlašovacího formuláře
    $loginUsername = $_POST['loginUsername'];
    $loginPassword = $_POST['loginPassword'];

    $xss_reg = '/[<>]/';

    preg_match($xss_reg, $loginUsername, $matches);
    if (count($matches) > 0) {

        echo '<script>alert("Usernamenesmí obsahovat < nebo >")</script>';
        echo '<script>window.location="../../index.php"</script>';
        die();
    }

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

            if($row["permission"] < 0){
                echo '<script>alert("Tento účet byl zablokován nebo smazán")</script>';
                echo '<script>window.location="../../index.php"</script>';
                die();
            }

            setcookie("user_id", $row["id"], time() + 1200, "/");
            setcookie("username", $row["username"], time() + 1200, "/");
            setcookie("perm", $row["permission"], time() + 1200, "/");

            // Pokud je přihlášený uživatel admin, přesměruje ho

            echo '<script>window.location="../../View/UserProfile.php"</script>';

            exit(); // Ukončí běh skriptu po přesměrování
        } else {
            echo '<script>alert("Nesprávné heslo")</script>';
            echo '<script>window.location="../../index.php"</script>';
        }

    } else {
        echo '<script>alert("Uživatel nenalezen")</script>';
        echo '<script>window.location="../../index.php"</script>';
    }

    // Uzavření připraveného dotazu
    $stmt->close();
}

// Uzavření spojení s databází
$dbConnection->closeConnection();
?>