<?php
use Services\Message\MessageManager;
require_once(__DIR__ . '/../Message/MessageManager.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'markAsDisplayed') {
    $messageId = isset($_GET['id']) ? $_GET['id'] : '';

    if (!empty($messageId)) {
        // Vytvoření instance MessageManageru
        $messageManager = new MessageManager();

        // Zavolání funkce na změnu na zobrazenu u zprávy
        $messageManager->markMessageAsDisplayed($messageId);

    }

    // Ukončení
    exit();
}

?>