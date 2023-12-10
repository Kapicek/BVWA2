<?php

use Services\Users\UserManager;

session_start();

if (isset($_COOKIE["user_id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["perm"])) {
     $username = $_COOKIE["username"];
} else {
    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    echo '<script>alert("Nejsi přihlášen!")</script>';
    echo '<script>window.location="../../index.php"</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Posílání zprávy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand mx-auto" href="../../View/Messages.php">
                <h1>Posílání zprávy</h1>
            </a>
        </div>
    </nav>
    <?php
    // Zobrazit úspěšnou zprávu
    if (isset($_SESSION['success_message'])) {
        echo '<div id="success-alert" class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']); // Smazat zprávu
    }

    // Zobrazit chybovou zprávu
    if (isset($_SESSION['error_message'])) {
        echo '<div id="error-alert" class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']); // Smazat zprávu
    }

    ?>
    <div class="container text-center">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Poslat zprávu</h5>
                <form action="process_message.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Uživatelské jméno</label>
                        <input type="text" class="form-control" name="receiverUsername" id="username"
                            placeholder="Uživatelské jméno" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Zpráva</label>
                        <textarea class="form-control" name="messageContent" id="message" rows="4"
                            placeholder="Napište svou zprávu zde" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Odeslat</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Automatické smazání alertu po určité době
            setTimeout(function () {
                var successAlert = document.getElementById('success-alert');
                var errorAlert = document.getElementById('error-alert');
                if (successAlert) {
                    successAlert.remove();
                }
                if (errorAlert) {
                    errorAlert.remove();
                }
            }, 5000); // 5 sekund
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>