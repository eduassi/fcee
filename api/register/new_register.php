<?php
// required headers

include_once '../global/cors_post.php';
// get database connection
include_once '../config/database.php';
include_once '../config/settings.php';

// instantiate product object
include_once '../objects/login.php';
include_once '../objects/register.php';

include_once '../global/utils.php';

// get posted data
// new_register

$data = json_decode(file_get_contents("php://input"));

$database = new Database();
$db = $database->getConnection();

$table_abstraction = new Register($db);
$table_abstraction->nome = $data->{"nome"};
$table_abstraction->orgaoEmissor = $data->{"orgao-emissor"};
$table_abstraction->curso = $data->{"curso"};
$table_abstraction->municipioReside = $data->{"municipio"};
$table_abstraction->localAtuacao = $data->{"local-atuacao"};
$table_abstraction->cpf = $data->{"cpf"};
$table_abstraction->rg = $data->{"rg"};
$table_abstraction->matricula = $data->{"matricula"};
$table_abstraction->email = $data->{"email"};
$table_abstraction->ddd = $data->{"ddd"};
$table_abstraction->telefone = $data->{"telefone"};
$table_abstraction->sexo = $data->{"sexo"};
$table_abstraction->estado = $data->{"estado"};
$table_abstraction->atuacao = $data->{"atuacao"};


$db->beginTransaction();
$query_result = $table_abstraction->new_register();

if ($query_result[0]) {
    $db->commit();
    $response = array("message" => getMessage($query_result[1]));
    http_response_code(201);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);

    // $new_folder_path = $VAULT_PATH . "/" . $decoded_token->userUniqueId . "/" . $new_course_folder_name;

    // if (mkdir($new_folder_path)) {
    //     try {
    //         $new_course["id"] = $course_folder_id;
    //         $new_course["name"] = $data->name;
    //         $new_course["description"] = $data->description;
    //         $new_course["created_at"] = $now_date;

    //         $result["active_courses"] = $owner_account["active_courses"] + 1;
    //         $result["new_course"] = $new_course;


    //         $db->commit();
    //         http_response_code(201);
    //         echo json_encode($result, JSON_UNESCAPED_UNICODE);
    //     } catch (Exception $e) {
    //         $db->rollBack();
    //         $response = array("message" => getMessage("genericFailure"));
    //         http_response_code(503);
    //         echo json_encode($response, JSON_UNESCAPED_UNICODE);
    //     }
    // } else {
    //     $db->rollBack();
    //     $response = array("message" => getMessage("genericFailure"));
    //     http_response_code(503);
    //     echo json_encode($response, JSON_UNESCAPED_UNICODE);
    // }
} else {
    $response = array("message" => $query_result[1]);
    http_response_code(503);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
