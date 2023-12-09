<?php
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
    $user = $userManager->getUserById($user_id);
} else {

    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    header('Location: ../Services/Users/process_login.php');
    exit();
} //TODO dodelej to Kubo uwu
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../userStyles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Profil</title>
</head>
<body>

<header>
    <h1> <?php echo 'Vítej uživateli ' . $user['username'] ?> </h1>
</header>

<section>
    <div id="user-card">
        <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>" alt="Profilová fotografie">
        <p>Jméno: <?= $user['firstName'] ?></p>
        <p>Příjmení: <?= $user['lastName'] ?></p>
        <p>Email: <?= $user['email'] ?></p>
        <p>Telefon: <?= $user['phone'] ?></p>
        <p>Pohlaví: <?= $user['gender'] ?></p>
        <p>Login: <?= $user['username'] ?></p>

        <button onclick="toggleEditMode()">Editovat profil</button>
    </div>

    <div id="editForm" style="display:none;">
        <form class="mt-3" method="post" action="/bvwa2/Services/Users/update_profile.php">
            <div class="form-group mb-3">
                <label for="firstName">Jméno:</label>
                <input type="text" class="form-control" name="firstName" value="<?= $user['firstName']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="lastName">Příjmení:</label>
                <input type="text" class="form-control" name="lastName" value="<?= $user['lastName']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input type="text" class="form-control" name="email" value="<?= $user['email']; ?>">
            </div>

            <div class="form-group mb-3">
                <label for="phone">Telefon:</label>
                <input type="text" class="form-control" name="phone" value="<?= $user['phone']; ?>">
            </div>

            <button type="submit" class="btn btn-primary">Uložit změny</button>
            <button type="button" class="btn btn-danger" onclick="toggleEditMode()">Zrušit změny</button>
        </form>
    </div>

    <script>
            function toggleEditMode() {
                let viewMode = document.getElementById('user-card');
                let editForm = document.getElementById('editForm');

                if (viewMode.style.display === 'none') {
                    viewMode.style.display = 'block';
                    editForm.style.display = 'none';
                    console.log("edit mod")
                } else {
                    viewMode.style.display = 'none';
                    editForm.style.display = 'block';
                    console.log("view mod")
                }
            }
        </script>
    </section>
    </body>
    </html>