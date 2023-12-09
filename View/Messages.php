<?php
use Services\Message\MessageManager;


session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Importujte třídu MessageManager
    require_once(__DIR__ . '/../Services/Message/MessageManager.php');

    // Vytvořte instanci třídy MessageManager
    $messageManager = new MessageManager();

    // Získání všech zpráv pro uživatele
    $allMessages = $messageManager->getAllMessagesForUser($user_id);
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
    <title>Zprávy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand mx-auto" href="#">
                <h1>Zprávy</h1>
            </a>
        </div>
    </nav>
    <section class="mb-4">
        <?php foreach ($allMessages as $message) { ?>
            <div class="card mb-3 bg-dark text-light">
                <div class="card-header">
                    <?php echo $message['krestni'] . ' ' . $message['prijmeni']; ?> vám posílá zprávu:
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <?php echo $message['content']; ?>
                    </p>
                </div>
            </div>
        <?php } ?>
    </section>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>