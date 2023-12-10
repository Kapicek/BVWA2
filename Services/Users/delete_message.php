<?php
use Services\Message\MessageManager;

require_once(__DIR__ . '/../Message/MessageManager.php');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'deleteMessage') {
    $messageId = isset($_GET['id']) ? $_GET['id'] : '';
    $deletedFor = isset($_GET['deletedFor']) ? $_GET['deletedFor'] : '';
    var_dump($messageId, $deletedFor);
    if (!empty($messageId) || !empty($messageId)) {
        // Vytvoření instance MessageManageru
        $messageManager = new MessageManager();

        //Smazání zprávy
        $messageManager->deleteDisplayedMessage($messageId, $deletedFor);
    }

    // Ukončení
    exit();
}
?>
