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
            <input type="text" class="form-control" name="email" value="<?= $user['email']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="phone">Telefon:</label>
            <input type="text" class="form-control" name="phone" value="<?= $user['phone']; ?>" readonly>
        </div>

        <div class="form-group mb-3">
            <button type="button" class="btn btn-primary" id="editButton" onclick="toggleEditMode()">Editovat profil</button>
            <button type="button" class="btn btn-success" id="saveChangesButton" onclick="saveChanges()" style="display: none;">Uložit změny</button>
            <button type="button" class="btn btn-secondary" id="cancelButton" onclick="cancelChanges()" style="display: none;">Zrušit</button>
        </div>

        <div class="form-group mb-3">
            <form id="test" method="post" action="Messages.php">
                <button type="button" class="btn btn-primary" id="cancelButton" onclick="messagesPage()">InBox</button>
            </form>
        </div>

    </form>

    <script>
        const values = new Map();
        const form = document.getElementById('user-card');

        function toggleEditMode() {
            let userCard = document.getElementById('user-card');
            let inputs = userCard.querySelectorAll('.form-control');
            let editButton = document.getElementById('editButton');
            let saveChangesButton = document.getElementById('saveChangesButton');
            let cancelButton = document.getElementById('cancelButton');

            userCard.removeAttribute('readonly');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                values.set(input.name, input.value);
            });
            editButton.style.display = 'none';
            saveChangesButton.style.display = 'block';
            cancelButton.style.display = 'block';
            console.log(values);
        }

        function saveChanges() {
            document.getElementById('user-card').submit();
        }

        function messagesPage() {
            window.location.href = "Messages.php";
        }

        function cancelChanges() {
            let userCard = document.getElementById('user-card');
            let inputs = userCard.querySelectorAll('.form-control');
            let editButton = document.getElementById('editButton');
            let saveChangesButton = document.getElementById('saveChangesButton');
            let cancelButton = document.getElementById('cancelButton');

            inputs.forEach(input => {
                console.log(input);
                input.value = values.get(input.name);
                input.setAttribute('readonly', 'true');
            });
            userCard.setAttribute('readonly', 'true');
            editButton.style.display = 'block';
            saveChangesButton.style.display = 'none';
            cancelButton.style.display = 'none';
            console.log(values);
        }
    </script>
</section>

</body>
</html>
