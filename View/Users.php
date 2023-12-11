<?php
// YourPageAfterLogin.php

use Services\Users\UserManager;

session_start();

if (isset($_COOKIE["user_id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["perm"]) && $_COOKIE["perm"] > 0) {
    $user_id = $_COOKIE["user_id"];
    $username = $_COOKIE["username"];
    $perm = $_COOKIE["perm"];

    setcookie("user_id", $user_id , time() + 1200, "/");
    setcookie("username", $username, time() + 1200, "/");
    setcookie("perm", $perm, time() + 1200, "/");

    // Importujte třídu UserManager
    require_once(__DIR__ . '/../Services/Users/UserManager.php');

    // Vytvořte instanci třídy UserManager
    $userManager = new UserManager();

    $encryptionKey = "tajny_klic_pro_sifrovani";

    // Získání všech uživatelů
    $allUsers = $userManager->getAllUsers();
} else {
    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    echo '<script>alert("Nejsi přihlášen, nebo nemáš oprávnění")</script>';
    echo '<script>window.location="../index.php"</script>';
    exit();
}

if (isset($_POST["sub"])) {

    setcookie("user_id", "", time() - 36000, "/");
    setcookie("username", "", time() - 36000, "/");
    setcookie("perm", "", time() - 36000, "/");
    echo '<script>alert("Byl jste odhlášen")</script>';
    echo '<script>window.location="../index.php"</script>';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../userStyles.css">
    <script src="/bvwa2/js/Users.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Všichni uživatelé</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="UserProfile.php">Profil</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="Messages.php">InBox</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <form action="#" method="POST">
                            <input type="submit" name="sub" class="btn btn-danger" value="Odhlásit">
                        </form>
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
            <form id="user-form" class="user-card form-group mb-5" method="post"
                action="/bvwa2/Services/Users/update_profile.php">
                <div class="row">
                    <div class="col-md-6">
                        <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>"
                            alt="Profilová fotografie" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($user["permission"] == 1) {
                            echo '<h5>Admin</h5>';
                        }
                        ?>
                    </div>
                </div>

                <dialog id="<?= $user['id'] ?>">
                    <p>Opravdu chceš uživatele smazat</p>
                    <div>
                        <button type="button" class="btn btn-danger" onclick="closeDialog(<?=$user['id']?>)">CANCEL</button>
                        <button type="button" class="btn btn-primary"
                                onclick="location.href='../Services/Users/change_permission.php?user_id=<?= $user['id'] ?>&lvl=-1'">
                            OK
                        </button>
                    </div>
                </dialog>

                <div class="form-group mb-3">
                    <label>Jméno:</label>
                    <input type="text" class="form-control" name="firstName" value="<?= $user['firstName']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label>Příjmení:</label>
                    <input type="text" class="form-control" name="lastName" value="<?= $user['lastName']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label id="usernameId">Uživatelské jméno:</label>
                    <input type="text" class="form-control" name="username" value="<?= $user['username']; ?> ">
                </div>

                <div class="form-group mb-3">
                    <label>Pohlaví:</label>
                    <input type="text" class="form-control" name="gender" value="<?= $user['gender']; ?>">
                </div>

                <div class="form-group mb-3">
                    <label>Email:</label>
                    <input id="emailInputId" type="text" class="form-control" name="email" value="<?= openssl_decrypt($user['email'], 'aes-256-cbc', $encryptionKey, 0, $user['key_iv']); ?>">
                </div>

                <div class="form-group mb-3">
                    <label>Telefon:</label>
                    <input id="phoneInputId" type="text" class="form-control" name="phone" value="<?= openssl_decrypt($user['phone'], 'aes-256-cbc', $encryptionKey, 0, $user['key_iv']) ?>">
                </div>

                <input type="hidden" name="page" value="users">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                <button type="button" class="btn btn-primary"
                    onclick="location.href='../Services/Message/SendMessage.php?receiver_id=<?= $user['id'] ?>'">
                    Poslat zprávu
                </button>
                <button type="submit" class="btn btn-primary">Uložit změny</button>
                <button type="button" class="btn btn-secondary"
                    onclick="location.href='../Services/Users/change_permission.php?user_id=<?= $user['id'] ?>&lvl=<?= $user['permission'] > 0 ? 0 : 1 ?>'">
                    <?php
                    echo $user['permission'] > 0 ? 'Demote' : 'Promote';
                    ?>
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete(<?=$user['id']?>)">Smazat</button>
            </form>

        <?php endforeach; ?>
    </section>

    <script>
        function confirmDelete(id) {
            const dialog = document.getElementById(id);
            if(dialog) {
                dialog.open = true;
            }
        }
        function closeDialog(id) {
            const dialog = document.getElementById(id);
            if(dialog) {
                dialog.open = false;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
</body>

</html>