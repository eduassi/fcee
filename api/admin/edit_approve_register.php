<?php
// required headers

include_once '../global/cors_post.php';
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
    $decoded_token = decodeToken($token);
    $data = json_decode(file_get_contents("php://input"));

    $database = new Database();
    $db = $database->getConnection();

    $table_abstraction = new Register($db);
    $table_abstraction->id = $_POST["id"];
    $table_abstraction->nome = $_POST["nome"];
    $table_abstraction->orgaoEmissor = $_POST["orgaoEmissor"];
    $table_abstraction->curso = $_POST["curso"];
    $table_abstraction->municipioReside = $_POST["municipioReside"];
    $table_abstraction->localAtuacao = $_POST["localAtuacao"];
    $table_abstraction->cpf = $_POST["cpf"];
    $table_abstraction->rg = $_POST["rg"];
    $table_abstraction->matricula = $_POST["matricula"];
    $table_abstraction->email = $_POST["email"];
    $table_abstraction->ddd = $_POST["ddd"];
    $table_abstraction->telefone = $_POST["telefone"];
    $table_abstraction->sexo = $_POST["sexo"];
    $table_abstraction->estado = $_POST["estado"];
    $table_abstraction->atuacao = $_POST["atuacao"];
    $table_abstraction->instituicaoAtuacao = $_POST["instituicaoAtuacao"];

    $query_result = $table_abstraction->edit_approve_register();

    if ($query_result) {
        // send_approval_email($table_abstraction->getName(), $table_abstraction->getEmail());

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
