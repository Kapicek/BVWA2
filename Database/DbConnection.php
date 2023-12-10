<?php

namespace Database;
use mysqli;

class DbConnection
{
    private const SERVERNAME = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const DBNAME = "bvwa2";
    private $conn;

    // Konstruktor
    public function __construct()
    {
        $this->conn = new mysqli(self::SERVERNAME, self::USERNAME, self::PASSWORD, self::DBNAME);

        // Kontrola připojení
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Nastavení kódování na utf8mb4
        $this->conn->set_charset("utf8mb4");
    }

    // Získání připojení
    public function getConnection()
    {
        return $this->conn;
    }

    // Uzavření připojení
    public function closeConnection()
    {
        $this->conn->close();
    }
}

?>