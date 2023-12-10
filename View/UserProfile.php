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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../userStyles.css">
    <script src="/bvwa2/js/UserProfile.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Profil</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo $user['username'] ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Messages.php">InBox</a>
                </li>
                <?php
                    if($user['permission'] == 1) {
                        echo '<li class="nav-item"> <a class="nav-link" href="Users.php">Uzivatele</a> </li>';
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
<section>
    <form id="user-card" class="mt-3" method="post" action="/bvwa2/Services/Users/update_profile.php">
        <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>" alt="Profilová fotografie">

        <div class="form-group mb-3">
            <label for="firstName">Jméno:</label>
            <input type="text" class="form-control" name="firstName" value="<?= $user['firstName']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="lastName">Příjmení:</label>
            <input type="text" class="form-control" name="lastName" value="<?= $user['lastName']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="lastName">Uživatelské jméno:</label>
            <input type="text" class="form-control" name="username" value="<?= $user['username']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="lastName">Pohlaví:</label>
            <input type="text" class="form-control" name="gender" value="<?= $user['gender']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input id="emailInput" type="text" class="form-control" name="email" value="<?= $user['email']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="phone">Telefon:</label>
            <input id="phoneInput" type="text" class="form-control" name="phone" value="<?= $user['phone']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <button type="button" class="btn btn-primary" id="editButton" onclick="toggleEditMode()">Editovat profil</button>
            <button type="button" class="btn btn-success" id="saveChangesButton" onclick="saveChanges()" style="display: none;">Uložit změny</button>
            <button type="button" class="btn btn-secondary" id="cancelButton" onclick="cancelChanges()" style="display: none;">Zrušit</button>
        </div>

    </form>
</section>

</body>
</html>
