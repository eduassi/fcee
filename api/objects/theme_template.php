<?php
class ThemeTemplate
{

    // database connection and table name
    private $conn;
    private $table_name = "theme_templates";

    // object properties
    private $id;
    private $name;
    private $description;
    private $slug;
    private $acessibility;
    private $darkmode;
    private $onepage;
    private $fullpage;

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
                case "description":
                    $value = htmlspecialchars(strip_tags($value));
                    break;
                default:
                    $value = $value;
            }
            $this->$property = $value;
        }
    }


    public function get_templates()
    {
        $query = "SELECT * FROM " . $this->table_name;
        
        // prepare query
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function get_template()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

}
