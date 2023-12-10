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
    <script src="/bvwa2/js/Users.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Všichni uživatelé</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="UserProfile.php">Profil</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                    <a class="nav-link" href="../index.php">Odhlásit</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <?php
        $error = $_SESSION['error_user_update'] ?? null;
        if ($error != null) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            unset($_SESSION['error_user_update']); // Smazat zprávu
        }
    ?>
    <section>
    <?php foreach ($allUsers as $user): ?>
        <form id="user-form" class="user-card form-group mb-5" method="post" action="/bvwa2/Services/Users/update_profile.php">
            <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>" alt="Profilová fotografie">

            <div class="form-group mb-3">
                <label for="firstName">Jméno:</label>
                <input type="text" class="form-control" name="firstName" value="<?= $user['firstName']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="lastName">Příjmení:</label>
                <input type="text" class="form-control" name="lastName" value="<?= $user['lastName']; ?>" >
            </div>

            <div class="form-group mb-3">
                <label id="usernameId" for="lastName">Uživatelské jméno:</label>
                <input type="text" class="form-control" name="username" value="<?= $user['username']; ?> ">
            </div>

            <div class="form-group mb-3">
                <label for="lastName">Pohlaví:</label>
                <input type="text" class="form-control" name="gender" value="<?= $user['gender']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input id="emailInputId" type="text" class="form-control" name="email" value="<?= $user['email']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="phone">Telefon:</label>
                <input id="phoneInputId" type="text" class="form-control" name="phone" value="<?= $user['phone']; ?>">
            </div>

            <input type="hidden" name="page" value="users">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

            <button type="button" class="btn btn-primary" onclick="location.href='../Services/Message/SendMessage.php?receiver_id=<?= $user['id'] ?>'">
                Poslat zprávu
            </button>
            <button type="submit" class="btn btn-primary" ">Uložit změny</button>
            <button type="button" class="btn btn-danger" onclick="location.href='../Services/Users/process_delete.php?user_id=<?= $user['id'] ?>'">
                Smazat
            </button>
        </form>

    <?php endforeach; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous">
</script>
</body>
</html>
