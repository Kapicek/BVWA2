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
    <?php foreach ($allUsers as $user): ?>
        <div class="user-card">
            <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>" alt="Profilová fotografie">
            <p>ID: <?= $user['id'] ?></p>
            <p>Jméno: <?= $user['firstName'] ?></p>
            <p>Příjmení: <?= $user['lastName'] ?></p>
            <p>Email: <?= $user['email'] ?></p>
            <p>Telefon: <?= $user['phone'] ?></p>
            <p>Pohlaví: <?= $user['gender'] ?></p>
            <p>Login: <?= $user['username'] ?></p>
            <a href="../Services/Message/SendMessage.php?receiver_id=<?= $user['id'] ?>">Poslat zprávu</a>
        </div>
    <?php endforeach; ?>
</section>

</body>
</html>
