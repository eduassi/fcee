<?php
class Course
{

    // database connection and table name
    private $conn;
    private $table_name = "courses";

    // object properties
    private $id;
    private $name;
    private $description;
    private $theme;
    private $owner;
    private $created_at;

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


    public function new_course()
    {
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " (name, description, owner) VALUES (:name, :description, :owner)";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind valuesc
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":owner", $this->owner);

        if ($stmt->execute()) {
            return true;
        }
        echo var_dump($stmt->errorInfo());
        return false;
    }

    public function delete_course()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id AND owner=:owner";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":owner", $this->owner);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function get_course()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=:id AND owner=:owner";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":owner", $this->owner);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function get_account_courses()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE owner=:owner";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":owner", $this->owner);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }

    public function update_basic_info($owner_uuid)
    {
        $query = "UPDATE " . $this->table_name . " INNER JOIN account ON " . $this->table_name . ".owner = account.id AND " . $this->table_name . ".id = :id AND account.unique_id = :owner_uuid SET " . $this->table_name . ".name = :name, " . $this->table_name . ".description = :description";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);

        $stmt->bindParam(":owner_uuid", $owner_uuid);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function set_template($owner_uuid)
    {
        $query = "UPDATE " . $this->table_name . " INNER JOIN account ON " . $this->table_name . ".owner = account.id AND " . $this->table_name . ".id = :id AND account.unique_id = :owner_uuid SET " . $this->table_name . ".theme = :template";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":template", $this->theme);

        $stmt->bindParam(":owner_uuid", $owner_uuid);

        if ($stmt->execute()) {
            return $stmt;
        }

        return false;
    }
}
