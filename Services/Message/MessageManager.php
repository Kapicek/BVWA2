<?php
// MessageManager.php

namespace Services\Message;

use Database\DbConnection;

require_once(__DIR__.'/../../Database/DbConnection.php');

class MessageManager
{
    private $dbConnection;

    public function __construct()
    {
        // Vytvořit instanci třídy pro připojení k databázi
        $this->dbConnection = new DbConnection();
    }

    public function saveMessage($sender_id, $receiver_id, $content)
    {
        $conn = $this->dbConnection->getConnection();

        // Ochrana před SQL injection
        $sender_id = mysqli_real_escape_string($conn, $sender_id);
        $receiver_id = mysqli_real_escape_string($conn, $receiver_id);
        $content = mysqli_real_escape_string($conn, $content);

        // Uložení zprávy do databáze
        $sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES ('$sender_id', '$receiver_id', '$content')";
        $result = $conn->query($sql);

        // Uzavření spojení s databází
        $this->dbConnection->closeConnection();

        return $result;
    }

    public function getAllMessagesForUser($user_id)
    {
        $conn = $this->dbConnection->getConnection();

        // Ochrana před SQL injection
        $user_id = mysqli_real_escape_string($conn, $user_id);

        // Získání všech zpráv pro konkrétního uživatele
        $sql = "SELECT messages.*, users.firstName AS krestni, users.lastName AS prijmeni
            FROM messages
            JOIN users ON messages.sender_id = users.id
            WHERE messages.receiver_id = '$user_id'";
        $result = $conn->query($sql);

        $messages = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }

        // Uzavření spojení s databází
        $this->dbConnection->closeConnection();

        return $messages;
    }
}

?>
