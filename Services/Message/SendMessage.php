<?php

use Services\Users\UserManager;

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    // Import třídy UserManager
    require_once(__DIR__.'/../Users/UserManager.php');

    // Vytvoření instance třídy UserManager
    $userManager = new UserManager();

    // Získání informací o přihlášeném uživateli
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    // Získání informací o příjemci z URL parametru
    $receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;

    // Získání informací o příjemci
    $receiverInfo = $userManager->getUserById($receiver_id);

    if (!$receiverInfo) {
        echo "Příjemce nenalezen.";
        exit();
    }
} else {
    // Pokud uživatel není přihlášen, přesměrujte ho na přihlašovací stránku
    //header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Poslat zprávu</title>
</head>
<body>

    <header>
        <h1>Poslat zprávu uživateli <?php echo $receiverInfo['username']; ?></h1>
    </header>

    <section>
        <form action="process_message.php" method="post">
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">

            <label for="messageContent">Obsah zprávy:</label>
            <textarea id="messageContent" name="messageContent" rows="4" cols="50" required></textarea>

            <div class="form-buttons">
                <button type="submit">Poslat zprávu</button>
            </div>
        </form>
    </section>

</body>
</html>
