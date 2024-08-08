<?php
// required headers

include_once '../global/cors_get.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/register.php';

include_once '../global/utils.php';

$TUTORS = 15;

$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Register($db);
$query_result = $table_abstraction->get_accepted_registers_to_csv();

if ($query_result) {
    $students_computed = 0;
    $student_group = 1;
    $enrolled = $query_result->rowCount();
    $students_per_group = $enrolled / $TUTORS;
    
    $query_result = $query_result->fetchAll(PDO::FETCH_ASSOC);
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $CSV_FILE_NAME . '"');

    fputcsv($fp, array_map("utf8_decode", array("username", "firstname", "lastname", "email", "profile_field_CPF", "course1", "type1", "group1")));
    
    foreach ($query_result as $row) {
        if(!$row["firstname"]){
            $row["firstname"] = $row["lastname"];
            $row["lastname"] = "";
        }
        
        $row["course1"] = "EEB0001";
        $row["type1"] = "1";
        $row["group1"] = $student_group;
        fputcsv($fp, array_map("utf8_decode", $row));
        $students_computed++;
        $student_group = ceil($students_computed / $students_per_group);
    }
}
