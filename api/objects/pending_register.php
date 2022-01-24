<?php
class Peding_Register
{

    // database connection and table name
    private $conn;
    private $table_name = "pending_register";

    // object properties
    private $id;
    private $username;
    private $password;
    private $email;
    private $account_type;
    private $secret_code;
    private $request_date;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //// SETTERS
    public function __set($property, $value)
    {
        if (property_exists($this, $property) && (!empty($value) || $value == 0)) {
            switch ($property) {
                case "email":
                    $value = trim($value);
                    $value = stripslashes($value);
                    $value = htmlspecialchars($value);
                    break;
                case "username":
                    $value = htmlspecialchars(strip_tags($value));
                    $value = str_replace(' ', '', $value);
                    $value = str_replace('.', '', $value);
                    $value = str_replace(';', '', $value);
                    $value = str_replace(',', '', $value);
                    break;
                default:
                    $value = $value;
            }
            $this->$property = $value;
        }
    }

    public function register_pending_signup()
    {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " (username, password, email, account_type, secret_code) VALUES (:username, :password, :email, :account_type, :secret_code)";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind valuesc
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":account_type", $this->account_type);
        $stmt->bindParam(":secret_code", $this->secret_code);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function check_avaiable()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE secret_code=:secret_code AND username=:username";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":secret_code", $this->secret_code);
        $stmt->bindParam(":username", $this->username);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function discard_pending()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE secret_code=:secret_code AND password=:password AND username=:username";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":secret_code", $this->secret_code);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":username", $this->username);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }
}
