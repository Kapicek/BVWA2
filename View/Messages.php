<?php
use Services\Message\MessageManager;

session_start();

if (isset($_COOKIE["user_id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["perm"]) && $_COOKIE["perm"] >= 0) {
    $user_id = $_COOKIE["user_id"];
    $username = $_COOKIE["username"];
    $perm = $_COOKIE["perm"];

    setcookie("user_id", $user_id , time() + 1200, "/");
    setcookie("username", $username, time() + 1200, "/");
    setcookie("perm", $perm, time() + 1200, "/");

    // Importujte třídu MessageManager
    require_once(__DIR__ . '/../Services/Message/MessageManager.php');
    require_once(__DIR__ . '/../Services/Users/UserManager.php');

    // Vytvořte instanci třídy MessageManager
    $messageManager = new MessageManager();

    // Získání všech zpráv pro uživatele
    $allReceivedMessages = $messageManager->getAllMessagesForUser($user_id);
    $allSendedMessages = $messageManager->getAllMessagesByUser($user_id);
    //$userManager = new UserManager();

    //$user = $userManager->getUserById($user_id);

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
    <title>Zprávy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .hidden {
            display: none;
        }

        .message-header {
            cursor: pointer;
        }

        #smazat {
            float: right;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        <form action="#" method="POST">
                            <input type="submit" name="sub" class="btn btn-danger" value="Odhlásit">
                        </form>
                    </li>
                </ul>
            </div>
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
    <div class="container-fluid">
        <div class="row">
            <!-- Tlačítko pro změnu na doručeno a odesláno -->
            <aside class="col-md-2">
                <div class="d-flex flex-column align-items-center">
                    <button onclick="location.href='../Services/Message/SendMessage.php'"
                        class="btn btn-danger mb-2">Nová zpráva</button>
                    <button onclick="showSection('received')" class="btn btn-success mb-2">Doručené</button>
                    <button onclick="showSection('sended')" class="btn btn-primary mb-2">Odeslané</button>
                </div>
            </aside>

            <!-- Zprávy -->
            <main class="col-md-10">
                <section class="mb-4" id="received">
                    <?php foreach ($allReceivedMessages as $message) {
                        if ($message['deletedFor'] != $user_id) {
                            ?>
                            <div
                                class="card mb-3 <?php echo ($message['is_displayed'] == 1) ? 'bg-gray text-dark' : 'bg-dark text-light'; ?>">
                                <div class="card-header message-header"
                                    onclick="toggleMessage(this, <?php echo $message['id']; ?>, <?php echo $message['is_displayed']; ?>, 1)">
                                    <?php echo $message['krestni'] . ' ' . $message['prijmeni']; ?> vám posílá zprávu:
                                </div>
                                <div class="card-body" style="display: none;">
                                    <p class="card-text">
                                        <?php echo $message['decryptedContent']; ?>
                                        <button id="smazat" class="btn btn-danger btn-sm"
                                            onclick="deleteMessage(<?php echo $message['id']; ?>,<?php echo $user_id; ?>)">Smazat zprávu</button>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </section>

                <section class="mb-4" id="sended">
                    <?php foreach ($allSendedMessages as $message) {
                        if ($message['deletedFor'] != $user_id) {
                            ?>
                            <div
                                class="card mb-3 <?php echo ($message['is_displayed'] == 1) ? 'bg-gray text-dark' : 'bg-dark text-light'; ?>">
                                <div class="card-header message-header"
                                    onclick="toggleMessage(this, <?php echo $message['id']; ?>, <?php echo $message['is_displayed']; ?>, 0)">
                                    Odeslaná zpráva pro:
                                    <?php echo $message['krestni'] . ' ' . $message['prijmeni']; ?>
                                </div>
                                <div class="card-body" style="display: none;">
                                    <p class="card-text">
                                        <?php echo $message['decryptedContent']; ?>
                                        <button id="smazat" class="btn btn-danger btn-sm"
                                            onclick="deleteMessage(<?php echo $message['id']; ?>,<?php echo $user_id; ?>)">Smazat zprávu</button>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </section>

            </main>
        </div>
    </div>



    <script>
        function toggleMessage(element, messageId, isDisplayed, isReceiver) {
            var cardBody = element.nextElementSibling;

            if (cardBody.style.display === 'none' || cardBody.style.display === '') {
                cardBody.style.display = 'block';
                if (isDisplayed === 0 && isReceiver == 1) {
                    markMessageAsDisplayed(messageId);
                }
            } else {
                cardBody.style.display = 'none';
            }
        }

        function deleteMessage(messageId, user_id) {
            console.log(user_id);
            deleteMessageAjax(messageId, user_id);
            location.reload();
        }

        function markMessageAsDisplayed(messageId) {
            // AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../Services/Users/update_message.php?action=markAsDisplayed&id=" + messageId, true);
            xhr.send();
        }

        function deleteMessageAjax(messageId, user_id) {
            // AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../Services/Users/delete_message.php?action=deleteMessage&id=" + messageId + "&deletedFor=" + user_id, true);
            xhr.send();
        }

        function showSection(sectionId) {
            // Skryj všechny sekce
            document.getElementById('received').classList.add('hidden');
            document.getElementById('sended').classList.add('hidden');
            // Zobraz vybranou sekci
            document.getElementById(sectionId).classList.remove('hidden');
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</body>

</html>