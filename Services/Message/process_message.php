<?php

// process_message.php

use Services\Message\MessageManager;

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_COOKIE["user_id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["perm"])) {
        // Import třídy MessageManager
        require_once(__DIR__.'/../Message/MessageManager.php');

        // Vytvoření instance třídy MessageManager
        $messageManager = new MessageManager();

        // Získání informací o odesílateli a příjemci
        $sender_id =$_COOKIE["user_id"];
        $username = $_COOKIE["username"];
        $receiverUsername = $_POST['receiverUsername'];
        if($username === $receiverUsername) {
            $_SESSION['error_message'] = 'Nemůžete posílat zprávu sám sobě';
            header('Location: SendMessage.php');
            exit();
        }

        // Získání obsahu zprávy
        $messageContent = $_POST['messageContent'];

        // Generování náhodného inicializačního vektoru (IV)
        try {
            $iv = bin2hex(random_bytes(16));
        } catch (\Random\RandomException $e) {
            $_SESSION['error_message'] = 'gg jsem jsi se nemel dostat';
            header('Location: SendMessage.php');
            exit();
        }

        // Klíč pro šifrování a dešifrování
        $encryptionKey = "tajny_klic_pro_sifrovani";

        // Šifrování obsahu zprávy
        $encryptedContent = openssl_encrypt($messageContent, 'aes-256-cbc', $encryptionKey, 0, hex2bin($iv));

        // Uložení zprávy do databáze
        $messageManager->saveMessage($sender_id, $receiverUsername, $encryptedContent, $iv);
        header('Location: SendMessage.php');
        exit();

    }
}
?>
