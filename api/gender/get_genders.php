<?php

include_once '../global/cors_get.php';

// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/gender.php';


$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Gender($db);
$waiting = $table_abstraction->get_genders();

if ($waiting) {
    $result = $waiting->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
} else {
    $response = array("message" => getMessage("genericFailure"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
