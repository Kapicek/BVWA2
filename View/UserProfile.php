<?php
use Services\Users\UserManager;

session_start();

if (isset($_COOKIE["user_id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["perm"])) {
    $user_id = $_COOKIE["user_id"];
    $username = $_COOKIE["username"];

    // Importujte třídu UserManager
    require_once(__DIR__ . '/../Services/Users/UserManager.php');

    // Vytvořte instanci třídy UserManager
    $userManager = new UserManager();

    // Klíč pro rozšifrování
    $encryptionKey = "tajny_klic_pro_sifrovani";

    // Získání všech uživatelů
    $user = $userManager->getUserById($user_id);
} else {

    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku

    echo '<script>alert("Nejsi přihlášen")</script>';
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
    <script src="/bvwa2/js/UserProfile.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Profil</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <?php echo $user['username'] ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="Messages.php">InBox</a>
                    </li>
                    <?php
                    if ($user['permission'] == 1) {
                        echo '<li class="nav-item"> <a class="nav-link float-left" href="Users.php">Uzivatele</a> </li>';
                    }
                    ?>
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
    <section>
        <form id="user-card" class="mt-3" method="post" action="/bvwa2/Services/Users/update_profile.php">
            <div class="container">
                <img src="data:image/jpeg;base64,<?= base64_encode($user['profilePic']) ?>" alt="Profilová fotografie" class="img-fluid" style="width: 200px; height: 200px;">
            </div>

            <div class="form-group mb-3">
                <label for="firstName">Jméno:</label>
                <input type="text" class="form-control" name="firstName" value="<?= $user['firstName']; ?>" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="lastName">Příjmení:</label>
                <input type="text" class="form-control" name="lastName" value="<?= $user['lastName']; ?>" readonly>
            </div>

            <div class="form-group mb-3">
                <?php
                $userExist = $_SESSION['error_user_update'] ?? null;
                unset($_SESSION['error_user_update']);
                if ($userExist != null) {
                    echo '<label id="usernameId" for="lastName" style="color: red;">Uživatelské jméno: již existuje</label>';
                } else {
                    echo '<label for="lastName">Uživatelské jméno:</label>';
                }
                echo '<input type="text" class="form-control" name="username" value="' . $user['username'] . '" readonly>';
                ?>
            </div>

            <div class="form-group mb-3">
                <label for="lastName">Pohlaví:</label>
                <input type="text" class="form-control" name="gender" value="<?= $user['gender']; ?>" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="email">Email:</label>
                <input id="emailInput" type="text" class="form-control" name="email" value="<?= openssl_decrypt($user['email'], 'aes-256-cbc', $encryptionKey, 0, $user['key_iv']); ?>"
                    readonly>
            </div>

            <div class="form-group mb-3">
                <label for="phone">Telefon:</label>
                <input id="phoneInput" type="text" class="form-control" name="phone" value="<?= openssl_decrypt($user['phone'], 'aes-256-cbc', $encryptionKey, 0, $user['key_iv']); ?>"
                    readonly>
            </div>

            <input type="hidden" name="page" value="profile">

            <div class="form-group mb-3">
                <button type="button" class="btn btn-primary" id="editButton" onclick="toggleEditMode()">Editovat
                    profil</button>
                <button type="button" class="btn btn-success" id="saveChangesButton" onclick="saveChanges()"
                    style="display: none;">Uložit změny</button>
                <button type="button" class="btn btn-secondary" id="cancelButton" onclick="cancelChanges()"
                    style="display: none;">Zrušit</button>
            </div>

        </form>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>