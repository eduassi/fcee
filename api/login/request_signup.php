<?php
// required headers

include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';
include_once '../global/utils.php';
include_once '../global/token_management.php';
include_once '../global/mail.php';

// instantiate product object
include_once '../objects/pending_register.php';

$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Peding_Register($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$checkData = array(
    "password" => array("value" => $data->password, "type" => "password"),
);
$checkResponse = checkConsistence($checkData);
if ($checkResponse["result"]) {
    $checking_uniquiness = false;

    $uniqueIdGenerated = generateRandom($data->username, $data->password, 10);
    $table_abstraction->email = $data->email;
    $table_abstraction->username = $data->username;
    $table_abstraction->password = password_hash($data->password, PASSWORD_DEFAULT);
    $table_abstraction->account_type = NULL;
    $table_abstraction->secret_code = $uniqueIdGenerated;

    $waiting = $table_abstraction->register_pending_signup();
    if ($waiting) {
        if ($TESTING_MODE || send_email($data->nickname, $uniqueIdGenerated, $data->email, $data->username, $data->password)) {
            $response = array("message" => "Pedido de cadastro feito com sucesso!");
            http_response_code(200);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Email Could not be sent!"));
        }

    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Pedido de registro já foi feito. Verifique seu E-mail (e sua caixa de SPAM) com as instruções ou espere alguns minutos para tentar novamente."));
    }

} else {
    http_response_code(400);
    echo json_encode(array("message" => $checkResponse["message"]));
}
