<?php

include_once '../global/cors_get.php';

// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../global/utils.php';
include_once '../global/token_management.php';

include_once '../objects/register.php';


$token = getBearerToken();
try {
    $decoded_token = decodeToken($token);

    $database = new Database();
    $db = $database->getConnection();

    $table_abstraction = new Register($db);
    $waiting = $table_abstraction->get_pending_registers();

    if ($waiting) {
        $result =  array(
            "registers" => $waiting->fetchAll(PDO::FETCH_ASSOC),
            "file-path" => $FILES_PATH,
            "picture-document-name" => $PICTURE_DOCUMENT_NAME,
            "comprovation-document-name" => $COMPROVATION_DOCUMENT_NAME,
        );
        http_response_code(200);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } else {
        $response = array("message" => getMessage("genericFailure"));
        http_response_code(401);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    echo $e;
    $response = array("message" => getMessage("tokenInactive"));
    http_response_code(401);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
