<?php
class Login
{

    // database connection and table name
    private $conn;
    private $table_name = "account";
    private $plan_table_name = "plan";

    // object properties
    private $id;

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

    

}
