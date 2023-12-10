<?php

use Database\DbConnection;

session_start();

// Importovat třídu DbConnection
require_once(__DIR__.'/../../Database/DbConnection.php');

$dbConnection = new DbConnection();
$conn = $dbConnection->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Získání dat z přihlašovacího formuláře
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_SESSION['user_id'];
    $count = 0;


    // Ochrana před SQL injection
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);

    $checkQuery = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bind_param("s", $username);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        // Uzivatel uz existuje
        header('Location: http://localhost/bvwa2/view/userprofile.php?userExist='.$count);
        exit();
    }


    $sql = "UPDATE users SET firstName = ?, lastName = ?,username = ? ,gender = ?, email = ?, phone = ? WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssss", $firstName, $lastName, $username , $gender, $email, $phone, $id);

    $result = $stmt->execute();

    // Execute the query
    if (!$result) {
        echo "L o L ";
    }

    $stmt->close();
    $dbConnection->closeConnection();

    header('Location: http://localhost/bvwa2/view/userprofile.php');

    exit();
}