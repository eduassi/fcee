<?php
// required headers

include_once '../global/cors_get.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/register.php';

include_once '../global/utils.php';


$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Register($db);
$query_result = $table_abstraction->get_accepted_registers_to_csv();

if ($query_result) {
    $query_result = $query_result->fetchAll(PDO::FETCH_ASSOC);
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $CSV_FILE_NAME . '"');

    fputcsv($fp, array_map("utf8_decode", array("username", "firstname", "lastname", "email", "profile_field_CPF", "course1", "type1")));
    foreach ($query_result as $row) {
        $row["course1"] = "EEB0001";
        $row["type1"] = "1";
        fputcsv($fp, array_map("utf8_decode", $row));
    }
}
