<?php
// required headers

include_once '../global/cors_get.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/register.php';

include_once '../global/utils.php';
include_once '../global/token_management.php';
include_once '../global/mail.php';


$token = getBearerToken();
try {
    $database = new Database();
    $db = $database->getConnection();

    $table_abstraction = new Register($db);
    $waiting = $table_abstraction->get_email_list();


    if ($waiting) {
        $targets = $waiting->fetchAll(PDO::FETCH_ASSOC);
        foreach ($targets as $row) {
            $email = $row["email"];
            $cpf = $row["cpf"];
            $name = $row["nome"];

            $upload_path = '../../documents/' . $cpf . '/';
            if (
                (is_dir($upload_path) && dir_is_empty($upload_path)) ||
                !file_exists($upload_path)
            ) {
                send_file_confirmation_email($name, $email);
            }
        }

        $response = array("message" => getMessage("genericSuccess"));
        http_response_code(201);
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
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
