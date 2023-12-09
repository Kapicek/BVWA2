<?php
// YourPageAfterLogin.php

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Importujte třídu UserManager
    require_once('UserManager.php');

    // Vytvořte instanci třídy UserManager
    $userManager = new UserManager();

    // Získání všech uživatelů
    $allUsers = $userManager->getAllUsers();
} else {
    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userStyles.css">
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
            echo '<img src="' . $user['profilePic'] . '" alt="Profilová fotografie">';
            echo '<p>ID: ' . $user['id'] . '</p>';
            echo '<p>Jméno: ' . $user['firstName'] . '</p>';
            echo '<p>Příjmení: ' . $user['lastName'] . '</p>';
            echo '<p>Email: ' . $user['email'] . '</p>';
            echo '<p>Telefon: ' . $user['phone'] . '</p>';
            echo '<p>Pohlaví: ' . $user['gender'] . '</p>';
            echo '<p>Login: ' . $user['username'] . '</p>';
            echo '<a href="SendMessage.php?receiver_id=' . $user['id'] . '">Poslat zprávu</a>';
            echo '</div>';
        }
        ?>
    </section>
</body>
</html>
