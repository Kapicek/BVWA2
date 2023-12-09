<?php

namespace Database;
use mysqli;

class DbConnection
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "bvwa2";
    private $conn;

    // Konstruktor
    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Kontrola připojení
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
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