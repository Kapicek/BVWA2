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

    // Ochrana před SQL injection
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);


    $sql = "UPDATE users SET firstName = ?, lastName = ?,username = ? ,gender = ?, email = ?, phone = ? WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("sssssss", $firstName, $lastName, $username , $gender, $email, $phone, $id);

    // Execute the query
    if (!$stmt->execute()) {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
    $dbConnection->closeConnection();

    header('Location: http://localhost/bvwa2/view/userprofile.php');

    exit();
}
