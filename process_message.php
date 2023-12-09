<?php

// process_message.php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        // Import třídy MessageManager
        require_once('MessageManager.php');

        // Vytvoření instance třídy MessageManager
        $messageManager = new MessageManager();

        // Získání informací o odesílateli a příjemci
        $sender_id = $_SESSION['user_id'];
        $receiver_id = $_POST['receiver_id'];

        // Získání obsahu zprávy
        $messageContent = $_POST['messageContent'];

        // Generování náhodného inicializačního vektoru (IV)
        $iv = bin2hex(random_bytes(16));

        // Klíč pro šifrování a dešifrování
        $encryptionKey = "tajny_klic_pro_sifrovani";

        // Šifrování obsahu zprávy
        $encryptedContent = openssl_encrypt($messageContent, 'aes-256-cbc', $encryptionKey, 0, hex2bin($iv));

        // Uložení zprávy do databáze
        $messageManager->saveMessage($sender_id, $receiver_id, $encryptedContent, $iv);

        echo "Zpráva byla úspěšně odeslána.";
    } else {
        echo "Uživatel není přihlášen.";
    }
}
?>
