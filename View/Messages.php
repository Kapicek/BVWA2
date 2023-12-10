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
    $allReceivedMessages = $messageManager->getAllMessagesForUser($user_id);
    $allSendedMessages = $messageManager->getAllMessagesByUser($user_id);
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
    <style>
        .hidden {
            display: none;
        }

        .message-header {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand mx-auto" href="UserProfile.php">
                <h1>Zprávy</h1>
            </a>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Tlačítko pro změnu na doručeno a odesláno -->
            <aside class="col-md-2">
                <div class="d-flex flex-column align-items-center">
                    <button onclick="location.href='../Services/Message/SendMessage.php'" class="btn btn-danger mb-2">Nová zpráva</button>
                    <button onclick="showSection('received')" class="btn btn-success mb-2">Doručené</button>
                    <button onclick="showSection('sended')" class="btn btn-primary mb-2">Odeslané</button>
                </div>
            </aside>

            <!-- Zprávy -->
            <main class="col-md-10">
                <section class="mb-4" id="received">
                    <?php foreach ($allReceivedMessages as $message) { ?>
                        <div
                            class="card mb-3 <?php echo ($message['is_displayed'] == 1) ? 'bg-gray text-dark' : 'bg-dark text-light'; ?>">
                            <div class="card-header message-header"
                                onclick="toggleMessage(this, <?php echo $message['id']; ?>, <?php echo $message['is_displayed']; ?>, 1)">
                                <?php echo $message['krestni'] . ' ' . $message['prijmeni']; ?> vám posílá zprávu:
                            </div>
                            <div class="card-body" style="display: none;">
                                <p class="card-text">
                                    <?php echo $message['decryptedContent']; ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </section>

                <section class="mb-4" id="sended">
                    <?php foreach ($allSendedMessages as $message) { ?>
                        <div
                            class="card mb-3 <?php echo ($message['is_displayed'] == 1) ? 'bg-gray text-dark' : 'bg-dark text-light'; ?>">
                            <div class="card-header message-header"
                                onclick="toggleMessage(this, <?php echo $message['id']; ?>, <?php echo $message['is_displayed']; ?>, 0)">
                                <?php echo $message['krestni'] . ' ' . $message['prijmeni']; ?> vám posílá zprávu:
                            </div>
                            <div class="card-body" style="display: none;">
                                <p class="card-text">
                                    <?php echo $message['decryptedContent']; ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>
                </section>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->

    <script>
        function toggleMessage(element, messageId, isDisplayed, isReceiver) {
            var cardBody = element.nextElementSibling;

            if (cardBody.style.display === 'none' || cardBody.style.display === '') {
                cardBody.style.display = 'block';
                if (isDisplayed === 0 && isReceiver == 1){
                    markMessageAsDisplayed(messageId);
                }
            } else {
                cardBody.style.display = 'none';
            }
        }

        function markMessageAsDisplayed(messageId) {
            // Make an AJAX request to call the PHP function
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "../Services/Users/update_message.php?action=markAsDisplayed&id=" + messageId, true);
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</body>

</html>