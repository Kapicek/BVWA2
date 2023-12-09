<?php
// UserManager.php

require_once('DbConnection.php');

class UserManager {
    private $dbConnection;

    public function __construct() {
        // Vytvořit instanci třídy pro připojení k databázi
        $this->dbConnection = new DbConnection();
    }

    public function getAllUsers() {
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

    public function getUserById($user_id) {
        $conn = $this->dbConnection->getConnection();

        // Získání informací o uživateli podle ID
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $sql = "SELECT * FROM users WHERE id = '$user_id'";
        $result = $conn->query($sql);

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
