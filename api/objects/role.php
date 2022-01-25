<?php
class Role
{

    // database connection and table name
    private $conn;

    // object properties
    private $id;
    private $name;

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
                case "name":
                    $value = htmlspecialchars(strip_tags($value));
                    break;
                default:
                    $value = $value;
            }
            $this->$property = $value;
        }
    }


    public function get_roles()
    {
        $query = "SELECT * FROM atuacao";

        // prepare query
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

}
