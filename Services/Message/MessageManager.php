<?php
// MessageManager.php

namespace Services\Message;

use Database\DbConnection;

require_once(__DIR__ . '/../../Database/DbConnection.php');

class MessageManager
{
    private $dbConnection;

    public function __construct()
    {
        // Vytvořit instanci třídy pro připojení k databázi
        $this->dbConnection = new DbConnection();
    }

    public function saveMessage($sender_id, $receiver_id, $content, $iv)
{
    $conn = $this->dbConnection->getConnection();

    $iv_bin = hex2bin($iv);
    // Příprava připraveného dotazu
    $sql = "INSERT INTO messages (sender_id, receiver_id, content, key_iv) VALUES (?, ?, ?, ?)";

    // Příprava a provedení připraveného dotazu
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $sender_id, $receiver_id, $content, $iv_bin);
    $result = $stmt->execute();

    // Uzavření spojení s databází
    $this->dbConnection->closeConnection();

    return $result;
}



public function getAllMessagesForUser($user_id)
{
    $conn = $this->dbConnection->getConnection();

    // Příprava připraveného dotazu
    $sql = "SELECT messages.*, 
                   users.firstName AS krestni, 
                   users.lastName AS prijmeni
            FROM messages
            JOIN users ON messages.sender_id = users.id
            WHERE messages.receiver_id = ?";

    // Připravení a provedení připraveného dotazu s bind_param
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();

     // Klíč pro šifrování a dešifrování
     $encryptionKey = "tajny_klic_pro_sifrovani";

    // Získání výsledků
    $result = $stmt->get_result();

    $messages = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Assuming $row['key_iv'] contains the IV (Initialization Vector)
            $iv = $row['key_iv'];

            // Assuming $row['content'] contains the encrypted content
            // Decrypt the content using openssl_decrypt
            $decryptedContent = openssl_decrypt($row['content'], 'aes-256-cbc', $encryptionKey, 0, $iv);

            // Add the decrypted content to the $row array
            $row['decryptedContent'] = $decryptedContent;

            // Add the modified row to the messages array
            $messages[] = $row;
        }
    }

    // Uzavření spojení s databází
    $this->dbConnection->closeConnection();

    return $messages;
}



}

?>