<?php
class Database
{
    private $db_server = 'localhost';
    private $db_username = 'root';
    private $db_db_password = '';
    private $db_name = 'sms_digital_db';
    private $conn;

    // Database connection
    public function db_connect()
    {
        $this->conn = null;

        $this->conn = new mysqli($this->db_server, $this->db_username, $this->db_db_password, $this->db_name);
        if ($this->conn->connect_errno)
        {
            echo "Errno: " . $this->conn->connect_errno . "\n";
            echo "Error: " . $this->conn->connect_error . "\n";
            exit;
        }

        return $this->conn;
    }

}
