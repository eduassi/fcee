<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;

    private $charset;
    public $conn;

    public function __construct()
    {

        $this->host = "localhost";
        $this->charset = "utf8";

        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $this->db_name = "eduassi_fcee";
            $this->username = "root";
            $this->password = "";
        } else {
            $this->db_name = "eduassi_fcee";
            $this->username = "eduassi_dba";
            $this->password = "eduassi8503";
        }
    }

    // get the database connection
    public function getConnection()
    {

        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset, $this->username, $this->password);
            $this->conn->exec("set names " . $this->charset);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
