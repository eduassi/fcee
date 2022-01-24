<?php
class Login
{

    // database connection and table name
    private $conn;
    private $table_name = "account";
    private $plan_table_name = "plan";

    // object properties
    private $id;
    private $unique_id;
    private $username;
    private $password;
    private $email;
    private $account_type;

    private $active_plan;
    private $active_courses;
    private $current_max_projects;
    private $expiration_date;

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

    public function get_account_by_unique_id()
    {

        $query = "SELECT * FROM " . $this->table_name . " WHERE unique_id=:unique_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind valuesc
        $stmt->bindParam(":unique_id", $this->unique_id);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function request_account()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username";

        // prepare query
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));

        // bind valuesc
        $stmt->bindParam(":username", $this->username);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function signin()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username=:username";

        // prepare query
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));

        // bind valuesc
        $stmt->bindParam(":username", $this->username);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function signup()
    {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " (username, password, unique_id, email, account_type) VALUES (:username, :password, :unique_id, :email, :account_type)";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind valuesc
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":unique_id", $this->unique_id);
        $stmt->bindParam(":email", $this->email);

        $stmt->bindParam(":account_type", $this->account_type);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function checkUniqueness($unique_id_test)
    {
        $query = "SELECT COUNT(unique_id) FROM " . $this->table_name . " WHERE unique_id=:unique_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":unique_id", $unique_id_test);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function get_account_plan_info()
    {
        $query = "SELECT name, max_projects, max_size, 	simultaneous_users, expiration_date, active_courses, current_max_projects FROM " . $this->table_name . " LEFT JOIN " . $this->plan_table_name . " ON " . $this->plan_table_name . ".id = " . $this->table_name . ".active_plan WHERE " . $this->table_name . ".unique_id = :unique_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":unique_id", $this->unique_id);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function increment_active_courses(){
        $query = "UPDATE " . $this->table_name . " SET `active_courses` = `active_courses` + 1 WHERE unique_id = :unique_id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":unique_id", $this->unique_id);

        if ($stmt->execute()) {
            return $stmt;
        }
        return false;
    }

    public function decrement_active_courses(){
        $query = "UPDATE " . $this->table_name . " SET `active_courses` = `active_courses` - 1 WHERE unique_id = :unique_id";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":unique_id", $this->unique_id);

        if ($stmt->execute()) {
            return $stmt;
        }
        return false;
    }
}
