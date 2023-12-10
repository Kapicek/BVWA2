<?php
// UserManager.php

namespace Services\Users;

use Database\DbConnection;

require_once(__DIR__ . '/../../Database/DbConnection.php');

class UserManager
{
    private $dbConnection;

    public function __construct()
    {
        // Vytvořit instanci třídy pro připojení k databázi
        $this->dbConnection = new DbConnection();
    }

    public function getAllUsers()
    {
        $conn = $this->dbConnection->getConnection();

        // Získání všech uživatelů z databáze
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        $users = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        // Uzavření spojení s databází
        $this->dbConnection->closeConnection();

        return $users;
    }

    public function getUserById($user_id)
    {
        $conn = $this->dbConnection->getConnection();

        // Připravený dotaz
        $sql = "SELECT * FROM users WHERE id = ?";

        // Příprava a provedení připraveného dotazu
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // Získání výsledků
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Uzavření spojení s databází
            $this->dbConnection->closeConnection();

            return $user;
        } else {
            // Uzavření spojení s databází
            $this->dbConnection->closeConnection();

            return null; // Uživatel nenalezen
        }
    }

}

?>