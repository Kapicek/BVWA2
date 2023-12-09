<?php
// YourPageAfterLogin.php

use Services\Users\UserManager;

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

     // Importujte třídu UserManager
     require_once(__DIR__.'/../Services/Users/UserManager.php');

    // Vytvořte instanci třídy UserManager
    $userManager = new UserManager();

    // Získání všech uživatelů
    $allUsers = $userManager->getAllUsers();
} else {
    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    header('Location: ../Services/Users/process_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../userStyles.css">
    <title>Všichni uživatelé</title>
</head>
<body>

    <header>
        <h1>Všichni uživatelé</h1>
    </header>

    <section>
        <?php
        foreach ($allUsers as $user) {
            echo '<div class="user-card">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($user['profilePic']) . '" alt="Profilová fotografie">'; //TODO Když to nepůjde -> opravuje Jakub Škrach
            echo '<p>ID: ' . $user['id'] . '</p>';
            echo '<p>Jméno: ' . $user['firstName'] . '</p>';
            echo '<p>Příjmení: ' . $user['lastName'] . '</p>';
            echo '<p>Email: ' . $user['email'] . '</p>';
            echo '<p>Telefon: ' . $user['phone'] . '</p>';
            echo '<p>Pohlaví: ' . $user['gender'] . '</p>';
            echo '<p>Login: ' . $user['username'] . '</p>';
            echo '<a href="../Services/Message/SendMessage.php?receiver_id=' . $user['id'] . '">Poslat zprávu</a>';
            echo '</div>';
        }
        ?>
    </section>
</body>
</html>
