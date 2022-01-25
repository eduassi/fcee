<?php
include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../global/utils.php';
include_once '../global/token_management.php';

// instantiate product object
include_once '../objects/login.php';
include_once '../objects/pending_register.php';

$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Login($db);
$pending_register = new Peding_Register($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$pending_register->username = $data->username;
$pending_register->secret_code = $data->code;

$check_code_credential = $pending_register->check_avaiable()->fetchAll(PDO::FETCH_ASSOC);

if (count($check_code_credential) == 1) {
    $fetch_result = $check_code_credential[0];
    $hashed_password = $fetch_result['password'];
    $password_match = password_verify($data->password, $hashed_password);

    if ($password_match) {
        $checking_uniquiness = false;
        do {
            $uniqueIdGenerated = rand() . $data->username . rand() . $data->password . rand();
            $uniqueIdGenerated = str_shuffle($uniqueIdGenerated);
            $uniqueIdGenerated = uniqid($uniqueIdGenerated, true);
            $uniqueIdGenerated = str_replace(".", "", $uniqueIdGenerated);
            $uniqueIdGenerated = substr($uniqueIdGenerated, 0, 49);
            $checking_uniquiness = $table_abstraction->checkUniqueness($uniqueIdGenerated);
        } while (!$checking_uniquiness && strlen($uniqueIdGenerated) != 50);

        $table_abstraction->username = $fetch_result["username"];
        $table_abstraction->password = $fetch_result["password"];
        $table_abstraction->email = $fetch_result["email"];
        $table_abstraction->account_type = NULL;
        $table_abstraction->unique_id = $uniqueIdGenerated;

        $waiting = $table_abstraction->signup();

        if ($waiting) {
            $pending_register->discard_pending();
            $token = generateToken($uniqueIdGenerated);
            $response = array(
                "token" => $token,
            );

            http_response_code(200);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Usuário já cadastrado!"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Dados incorretos!"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Dados incorretos!"));
}
