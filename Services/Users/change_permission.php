<?php

use Database\DbConnection;

session_start();

// Importovat třídu DbConnection
require_once(__DIR__.'/../../Database/DbConnection.php');

$dbConnection = new DbConnection();
$conn = $dbConnection->getConnection();

if(isset($_GET['user_id']) && isset($_GET['lvl']) ) {

    $userId = $_GET['user_id'];
    $permission = $_GET['lvl'];

    $checkQuery = "SELECT COUNT(*) FROM users WHERE id = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("i", $userId);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        // Uzivatel s timto id existuje, takze ho muzeme smazat
        $deleteQuery = "UPDATE users SET permission = ? WHERE id = ?";
        $stmtDelete = $conn->prepare($deleteQuery);
        $stmtDelete->bind_param("ss", $permission,$userId);
        $stmtDelete->execute();
        $stmtDelete->close();

        header('Location: http://localhost/bvwa2/view/users.php');
    } else {
        header('Location: http://localhost/bvwa2/view/users.php');
    }

    $conn->close();
    exit();
}