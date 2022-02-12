<?php
// required headers

include_once '../global/cors_put.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../global/utils.php';
include_once '../global/token_management.php';
include_once '../global/mail.php';

include_once '../objects/register.php';


$token = getBearerToken();
try {
    $decoded_token = decodeToken($token);

    $data = json_decode(file_get_contents("php://input"));

    $database = new Database();
    $db = $database->getConnection();



    $table_abstraction = new Register($db);

    $table_abstraction->id = $data->id;
    $table_abstraction->nome = $data->nome;
    $table_abstraction->email = $data->email;


    $query_result = $table_abstraction->accept_register();

    if (
        $query_result
    ) {
        send_approval_email($table_abstraction->getName(), $table_abstraction->getEmail());

        $response = array("message" => getMessage("genericSuccess"));
        http_response_code(201);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        $response = array("message" => getMessage("genericFailure"));
        http_response_code(503);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo $e;
    $response = array("message" => getMessage("tokenInactive"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
